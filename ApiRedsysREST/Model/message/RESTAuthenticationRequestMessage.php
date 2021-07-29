<?php
if (! class_exists ( 'RESTAuthenticationRequestMessage' )) {
	include_once $GLOBALS ["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	include_once $GLOBALS ["REDSYS_API_PATH"] . "/Model/RESTRequestInterface.php";
	
	/**
	 * @XML_ELEM=DATOSENTRADA
	 */
	class RESTAuthenticationRequestMessage extends RESTGenericXml implements RESTRequestInterface {
		
		/**
		 * 	DECLARATION OF VARS OF CLASS: "RESTAuthenticationRequestMessage" 
		*/

		/**
		 * @XML_ELEM=DS_MERCHANT_MERCHANTCODE
		 */
		private $merchant;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_TERMINAL
		 */
		private $terminal;

		/**
		 * @XML_ELEM=DS_MERCHANT_ORDER
		 */
		private $order;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_IDOPER
		 */
		private $operID;

		/**
		 * @XML_ELEM=DS_MERCHANT_TRANSACTIONTYPE
		 */
		private $transactionType;

		/**
		 * @XML_ELEM=DS_MERCHANT_CURRENCY
		 */
		private $currency;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_AMOUNT
		 */
		private $amount;
		
		/** 
		 * Card Number
		 */
		private $cardNumber;
	
		/**
		 * Card ExpiryDate
		 */
		private $cardExpiryDate;
	
		/**
		 * Card CVV2
		 */
		private $cvv2;
	
		/**
		 * Other operation parameter
		 */
		private $parameters = array ();

		/**
		 * 3DSecure information
		 * @XML_ELEM=DS_MERCHANT_EMV3DS
		 */
		private $emv = array();
		
		/**
		 * @XML_ELEM=
		 */
		private $dcc = array();
		/**
		 * GETTERS & SETTERS OF VARS
		*/
		
		//merchant
		public function getMerchant() {
			return $this->merchant;
		}
		public function setMerchant($merchant) {
			$this->merchant = $merchant;
			return $this;
		}		

		//terminal
		public function getTerminal() {
			return $this->terminal;
		}
		public function setTerminal($terminal) {
			$this->terminal = $terminal;
			return $this;
		}

		//order
		public function getOrder() {
			return $this->order;
		}
		public function setOrder($order) {
			$this->order = $order;
			return $this;
		}

		//operID
		public function getOperID() {
			return $this->operID;
		}
		public function setOperID($operID) {
			$this->operID = $operID;
			return $this;
		}

		//transactionType
		public function getTransactionType() {
			return $this->transactionType;
		}
		public function setTransactionType($transactionType) {
			$this->transactionType = $transactionType;
			return $this;
		}

		//currency
		public function getCurrency() {
			return $this->currency;
		}
		public function setCurrency($currency) {
			$this->currency = $currency;
			return $this;
		}

		//amount
		public function getAmount() {
			return $this->amount;
		}
		public function setAmount($amount) {
			$this->amount = $amount;
			return $this;
		}

		//cardNumber
		public function getCardNumber() {
			return $this->cardNumber;
		}
		public function setCardNumber($cardNumber) {
			$this->cardNumber = $cardNumber;
		}

		//cardExpiryDate
		public function getCardExpiryDate() {
			return $this->cardExpiryDate;
		}
		public function setCardExpiryDate($cardExpiryDate) {
			$this->cardExpiryDate = $cardExpiryDate;
		}

		//cvv2
		public function getCvv2() {
			return $this->cvv2;
		}
		public function setCvv2($cvv2) {
			$this->cvv2 = $cvv2;
		}

		//parameters
		public function getParameters() {
			return $this->parameters;
		}
		public function addParameter($key, $value) {
			$this->parameters [$key] = $value;
		}

		//emv
		public function getEmv(){
			if($this->emv==NULL)
				return null;
			
			return json_encode($this->emv);
		}
		public function setEmv($emv){
			$this->emv = $emv;
			return $this;
		}

		public function getDCC(){
			if($this->dcc==NULL)
				return null;
			
			return json_encode($this->dcc);
		}
		public function setDCC($dcc){
			$this->dcc = $dcc;
			return $this;
		}

		//Methods

		/**
		 * Flag for reference creation (card token for merchant to use in other operations)
		 */
		public function createReference() {
			$this->addParameter ( RESTConstants::$REQUEST_MERCHANT_IDENTIFIER, RESTConstants::$REQUEST_MERCHANT_IDENTIFIER_REQUIRED );
		}
		
		/**
		 * Method for using a reference created before for the operation
		 * reference the reference string to be used
		 */
		public function useReference($reference) {
			$this->addParameter ( RESTConstants::$REQUEST_MERCHANT_IDENTIFIER, $reference );
		}

		/**
		 * Function that after introducing a parameter stores it in the array $emv
		 */
		public function addEmvParameters($parameters){
			if($this->emv==NULL)
				$this->emv=array();

			foreach ($parameters as $key => $value)
				$this->emv[$key]=$value;
		}

		/**
		 * Function that after entering the name and value of a parameter stores it in the array $emv
		 */
		public function addEmvParameter($name, $value){
			if($this->emv==NULL)
				$this->emv=array();
			
			$this->emv[$name]=$value;
		}

		/**
		 * Function that after entering the name and value of a parameter stores it in the array $emv
		 */
		public function addDCCParameter($name, $value){
			if($this->dcc==NULL)
				$this->dcc=array();
			
			$this->dcc[$name]=$value;
		}
		
	
		/** 
		 * Method for the first COF operation
		 */	
		public function setCOFOperation($cofType) {
			
			$this->addParameter(RESTConstants::$REQUEST_MERCHANT_COF_INI, RESTConstants::$REQUEST_MERCHANT_COF_INI_TRUE);
			$this->addParameter(RESTConstants::$REQUEST_MERCHANT_COF_TYPE, $cofType);
		}
		
		/** 
		 * Method for a COF operation
		 */	
		public function setCOFTxnid($txnid) {
			$this->addParameter(RESTConstants::$REQUEST_MERCHANT_COF_TXNID, $txnid);
		}
	
		/** 
		 * demand DCC card info in the response 
		 */
		public function demandDccinfo() {
			$this->addDCCParameter(RESTConstants::$REQUEST_DS_MERCHANT_DCC, RESTConstants::$REQUEST_DS_MERCHANT_DCC_TRUE);
		}

		/** 
		 * Method for set the EMV3DS return parameters for a V1 challenge Request 
		 * $pares protocolVersion 1.0.2 authentication parameter
		 * $md protocolVersion 1.0.2 authentication parameter
		 */	
		public function challengeRequestV1($pares, $md) {
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSINFO, RESTConstants::$REQUEST_MERCHANT_EMV3DS_CHALLENGEREQUESTRESPONSE);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION, RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_PARES, $pares);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_MD, $md);
		}
				
		/** 
		 * Method for set the EMV3DS return parameters for a V2 challenge Request 
		 * $protocolVersion protocolVersion 2.X.0 authentication parameter 
		 * $cres protocolVersion 2.X.0 authentication parameter
		 */	
		public function challengeRequestV2($protocolVersion, $cres) {
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSINFO, RESTConstants::$REQUEST_MERCHANT_EMV3DS_CHALLENGEREQUESTRESPONSE);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION, $protocolVersion);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_CRES, $cres);
		}

	}
}