<?php
if (! class_exists ( 'RESTInitialRequestMessage' )) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/message/RESTOperationMessage.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Constants/RESTConstants.php";
	
	/**
	 * @XML_ELEM=REQUEST
	 */
	class RESTInitialRequestMessage extends RESTGenericXml {
		
		/**
		 * 	DECLARATION OF VARS OF CLASS: "RESTInitialRequestMessage" 
		*/

		/**
		 * Merchant code (FUC)
		 * @XML_ELEM=DS_MERCHANT_MERCHANTCODE
		 */
		private $merchant = null;
		
		/**
		 * Terminal code
		 * @XML_ELEM=DS_MERCHANT_TERMINAL
		 */
		private $terminal = null;
		
		/**
		 * Operation order code
		 * @XML_ELEM=DS_MERCHANT_ORDER
		 */
		private $order = null;
		
		/**
		 * Operation ID code
		 * @XML_ELEM=DS_MERCHANT_IDOPER
		 */
		private $operID = null;
		
		/**
		 * Operation type
		 * @XML_ELEM=DS_MERCHANT_TRANSACTIONTYPE
		 */
		private $transactionType = null;
		
		/**
		 * Currency code (ISO 4217)
		 * @XML_ELEM=DS_MERCHANT_CURRENCY
		 */
		private $currency = null;
		
		/**
		 * Operation amount, withot decimal separation
		 * @XML_ELEM=DS_MERCHANT_AMOUNT
		 */
		private $amount = null;
		
		/** DCC information */
		private $dcc = null;

		/**
		 * Other operation parameters
		 */
		private $parameters = array ();

		/**
		 * 3DSecure information
		 * @XML_ELEM=DS_MERCHANT_EMV3DS
		 */
		private $emv = null;

		/**
		 * Card Number
		 * @XML_ELEM=DS_MERCHANT_PAN
		 */
		private $cardNumber = null;
	
		/**
		 * Expiry Date
		 * @XML_ELEM=DS_MERCHANT_EXPIRYDATE
		 */
		private $cardExpiryDate = null;
			
		/**
		 * Expiry Date
		 * @XML_ELEM=DS_MERCHANT_CVV2
		 */
		private $cvv2 = null;
		
		//NO SALEN EN JAVA
		/////////////////////////////////////////////////////////////
		
		/**
		 * @XML_ELEM=Ds_MerchantParameters
		 */
		private $datosEntradaB64 = null;
		
		/**
		 * @XML_CLASS=RESTOperationMessage
		 */
		private $datosEntrada = null;
		
		/**
		 * @XML_ELEM=DS_SIGNATUREVERSION
		 */
		private $signatureVersion = null;
		
		/**
		 * @XML_ELEM=DS_SIGNATURE
		 */
		private $signature = null;
		
		/////////////////////////////////////////////////////////////


		/**
		 * GETTERS & SETTERS OF VARS
		*/


		//merchant

		/**
		 * gets the merchant code (FUC)
		 * return the merchant code
		 */
		public function getMerchant() {
			return $this->merchant;
		}
		
		/**
		 * sets the merchant code
		 * $merchant: merchant code
		 */
		public function setMerchant($merchant) {
			$this->merchant = $merchant;
			return $this;
		}
		

		//terminal

		/**
		 * gets the terminal code
		 * return the terminal code
		 */
		public function getTerminal() {
			return $this->terminal;
		}
		
		/**
		 * sets the terminal code
		 * $terminal: terminal code (max lenght 3)
		 */
		public function setTerminal($terminal) {
			$this->terminal = $terminal;
			return $this;
		}
		

		//order

		/**
		 * gets the operation order code (max length 12)
		 * return the operation order (max length 12)
		 */
		public function getOrder() {
			return $this->order;
		}
		
		/**
		 * sets the operation order (max length 12)
		 * $order: (max length 12)
		 */
		public function setOrder($order) {
			$this->order = $order;
			return $this;
		}
		

		//operID

		/**
		 * gets the operation ID
		 * return the operation ID
		 */
		public function getOperID() {
			return $this->operID;
		}

		/**
		 * sets the operation ID
		 * $operID: the operation ID
		 */
		public function setOperID($operID) {
			$this->operID = $operID;
			return $this;
		}
		

		//transactionType

		/**
		 * gets the operation type
		 * return the operation type
		 */
		public function getTransactionType() {
			return $this->transactionType;
		}
		
		/**
		 * sets the operation type
		 * $transactionType: the operation type
		 */
		public function setTransactionType($transactionType) {
			$this->transactionType = $transactionType;
			return $this;
		}
		

		//currency

		/**
		 * get currency code
		 * return the currency code (numeric ISO_4217)
		 */
		public function getCurrency() {
			return $this->currency;
		}
		
		/**
		 * sets the currency code
		 * $currency: the currency code (numeric ISO_4217 )
		 */
		public function setCurrency($currency) {
			$this->currency = $currency;
			return $this;
		}
		

		//amount

		/**
		 * gets the amount of the operation
		 * return the operation amount
		 */
		public function getAmount() {
			return $this->amount;
		}
		
		/**
		 * sets the amount of the operation
		 * $amount: without decimal separation
		 */
		public function setAmount($amount) {
			$this->amount = $amount;
			return $this;
		}


		//dcc

		/**
		 * gets if the DCC's flag is activated
		 */
		public function getDcc() {
			return $this->dcc;
		}
		/**
		 * mark as true the DCC's flag
		 */
		public function setDcc($dcc) {
			return $this->dcc = $dcc;
		}


		//parameters

		/**
		 * get other operation parameters
		 */
		public function getParameters() {
			return $this->parameters;
		}

		/**
		 * add a new parameter to the variable $parameters
		 */
		public function addParameter($key, $value) {
			$this->parameters [$key] = $value;
		}


		//emv

		/**
		 * emv
		 * return unkown
		 */
		public function getEmv(){
			if($this->emv==NULL)
				return null;
			
			return json_encode($this->emv);
		}
		
		/**
		 * emv
		 * unkown $emv
		 * return RESTOperationMessage
		 */
		public function setEmv($emv){
			$this->emv = $emv;
			return $this;
		}


		//cardNumber

		/**
		 * @return the cardNumber
		 */
		public function getCardNumber() {
			return $this->cardNumber;
		}

		/**
		 * @param cardNumber the cardNumber to set
		 */
		public function setCardNumber($cardNumber) {
			$this->cardNumber = $cardNumber;
		}


		//cardExpiryDate

		/**
		 * @return the cardExpiryDate
		 */
		public function getCardExpiryDate() {
			return $this->cardExpiryDate;
		}

		/**
		 * @param cardExpiryDate the cardExpiryDate to set
		 */
		public function setCardExpiryDate($cardExpiryDate) {
			$this->cardExpiryDate = $cardExpiryDate;
		}


		//cvv2
		/**
		 * @return the cvv2
		 */
		public function getCvv2() {
			return $this->cvv2;
		}

		/**
		 * @param cvv2 the cvv2 to set
		 */
		public function setCvv2($cvv2) {
			$this->cvv2 = $cvv2;
		}


		//METHODS


		/**
		 * Flag for reference creation (card token for merchant to use in other operations)
		 */
		public function createReference() {
			$this->addParameter ( RESTConstants::$REQUEST_MERCHANT_IDENTIFIER, RESTConstants::$REQUEST_MERCHANT_IDENTIFIER_REQUIRED );
		}
		
		/**
		 * Method for using a reference created before for the operation
		 * $reference: the reference string to be used
		 */
		public function useReference($reference) {
			$this->addParameter ( RESTConstants::$REQUEST_MERCHANT_IDENTIFIER, $reference );
		}

		public function addEmvParameters($parameters){
			if($this->emv==NULL)
				$this->emv=array();

			foreach ($parameters as $key => $value)
				$this->emv[$key]=$value;
		}

		public function addEmvParameter($name, $value){
			if($this->emv==NULL)
				$this->emv=array();
			
			$this->emv[$name]=$value;
		}

		/** Method for demand the CardData Authenticacion information
		 * $ThreeDsInfo Key Parameter
		 * $ThreeDsInfo Key Value
		 */
		public function demandCardData() {
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSINFO, RESTConstants::$REQUEST_MERCHANT_EMV3DS_CARDDATA);
		}

		/** 
	 	 * Method for demand the list of possible exemptions that the commerce can use
	 	 */
		public function demandExemptionInfo() {
			$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_YES);
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
		public function demandDCCinfo() {
		$this->addParameter(RESTConstants::$REQUEST_DS_MERCHANT_DCC, RESTConstantS::$REQUEST_DS_MERCHANT_DCC_TRUE);
		}
	
		public function __toString() {
			$string = "RESTInitialRequestMessage{";
			$string .= 'merchant: ' . $this->getMerchant () . ', ';
			$string .= 'terminal: ' . $this->getTerminal () . ', ';
			$string .= 'order: ' . $this->getOrder () . ', ';
			$string .= 'operID: ' . $this->getOperID () . ', ';
			$string .= 'transactionType: ' . $this->getTransactionType () . ', ';
			$string .= 'currency: ' . $this->getCurrency () . ', ';
			$string .= 'amount: ' . $this->getAmount () . ', ';
			$string .= 'dcc: ' . $this->getDcc() . '';
			$string .= 'emv: ' . $this->getEmv() . '';
			return $string . "}";
		}

						//NO SALEN EN JAVA
		/////////////////////////////////////////////////////////////
	
		function __construct() {
			$this->signatureVersion = RESTConstants::$REQUEST_SIGNATUREVERSION_VALUE;
		}
		
		public function getDatosEntrada() {
			return $this->datosEntrada;
		}
		public function setDatosEntrada($datosEntrada) {
			$this->datosEntrada = $datosEntrada;
			$this->datosEntradaB64 = base64_encode($this->datosEntrada->toJson());
			return $this;
		}
		public function getSignatureVersion() {
			return $this->signatureVersion;
		}
		public function sreVersion($signatureVersion) {
			$this->signatureVersion = $signatureVersion;
			return $this;
		}
		public function getSignature() {
			return $this->signature;
		}
		public function setSignature($signature) {
			$this->signature = $signature;
			return $this;
		}
		public function getDatosEntradaB64(){
			return $this->datosEntradaB64;
		}
		public function setDatosEntradaB64($datosEntradaB64){
			$this->datosEntradaB64 = $datosEntradaB64;
			return $this;
		}

		/////////////////////////////////////////////////////////////

	}

}
