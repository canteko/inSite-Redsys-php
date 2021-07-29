<?php
if (! class_exists ( 'RESTOperationMessage' )) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTRequestInterface.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Constants/RESTConstants.php";
	
	/**
	 * @XML_ELEM=DATOSENTRADA
	 */
	class RESTOperationMessage extends RESTGenericXml implements RESTRequestInterface {
		
		/**
		 * 	DECLARATION OF VARS OF CLASS: "RESTOperationMessage" 
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
		
		/**
		 * DCC indicator for DCC appliance
		 * @XML_ELEM=RESTDCCElement
		 */
		private $dcc;

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
		private $cardNumber;
		
		/**
		 * Expiry Date
		 * @XML_ELEM=DS_MERCHANT_EXPIRYDATE
		 */
		private $cardExpiryDate;
		
		/**
		 * Expiry Date
		 * @XML_ELEM=DS_MERCHANT_CVV2
		 */
		private $cvv2;

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
		 * gets the DCC of the operation
		 */
		public function getDcc() {
			return $this->dcc;
		}
		/**
		 * sets the DCC of the operation
		 */
		public function setDcc() {
			return $this->dcc = true;
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
			return $this->emv;
			/**if($this->emv==NULL)
				return null;
			
			return json_encode($this->emv);*/
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
		
		/**
		 * Flag for direct payment operation.
		 * Direct payment operation implies:
		 * 1) No-secure operation
		 * 2) No-DCC operative appliance
		 */
		public function useDirectPayment() {
			$this->addParameter ( RESTConstants::$REQUEST_MERCHANT_DIRECTPAYMENT, RESTConstants::$REQUEST_MERCHANT_DIRECTPAYMENT_TRUE );
		}
		
		/** 
		 * For use a MOTO Payment
		 */
		public function useMOTOPayment() {
			$this->addParameter(RESTConstants::$REQUEST_MERCHANT_DIRECTPAYMENT, RESTConstants::$REQUEST_MERCHANT_DIRECTPAYMENT_MOTO);
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
		
		/** DCC parameters 
		 * @param key key parameter
		 * @param value key value 
		 */
		public function addDCCParameter($key, $value) {
			
			$this->dcc[$key] = $value;
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
		 * method for a DCC operation 
		 */
		public function dccOperation($monedaDCC, $importeDCC) {
			
			$this->addDCCParameter(RESTConstants::$REQUEST_DS_MERCHANT_DCC_MONEDA, $monedaDCC);
			$this->addDCCParameter(RESTConstants::$REQUEST_DS_MERCHANT_DCC_IMPORTE, $importeDCC);
		}
		/**
		 * Flag for secure operation.
		 * If is used, after the response, the process will be stopped due to the authentication process
		 */
// 		public function useSecurePayment() {
// 			$this->addParameter ( RESTConstants::$REQUEST_MERCHANT_DIRECTPAYMENT, RESTConstants::$REQUEST_MERCHANT_DIRECTPAYMENT_3DS );
// 		}

		/** 
		 * Method for set the EMV3DS protocolVersionV1 parameters 
		 */	
		public function setEMV3DSParamsV1() {
			
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSINFO, RESTConstants::$REQUEST_MERCHANT_EMV3DS_AUTHENTICACIONDATA);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION, RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_ACCEPT_HEADER, RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_ACCEPT_HEADER_VALUE);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_USER_AGENT, RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_USER_AGENT_VALUE);
		}
		
		/**
		 * Method for set the EMV3DS protocolVersionV2 parameters 
		 * ---PARAMETERS---
		 * $protocolVersion: 3EMV3DS authentications version
		 * $browserAcceptHeader: 3DSMethod return value
		 * $browserUserAgent: 3DSMethod return value
		 * $browserJavaEnable: 3DSMethod return value
		 * $browserJavaScriptEnabled: 3DSMethod return value
		 * $browserLanguage: 3DSMethod return value
		 * $browserColorDepth: 3DSMethod return value
		 * $browserScreenHeight: 3DSMethod return value
		 * $browserScreenWidth: 3DSMethod return value
		 * $browserTZ 3DSMethod: return value
		 * $threeDSServerTransID: 3DSMethod return value
		 * $notificationURL: Authentication URL
		 * $threeDSCompInd: 3DSMethod return value
		 */
		public function setEMV3DSParamsV2($protocolVersion, $browserAcceptHeader, $browserUserAgent, $browserJavaEnable, 
				$browserJavaScriptEnabled, $browserLanguage, $browserColorDepth, $browserScreenHeight, $browserScreenWidth,
				$browserTZ, $threeDSServerTransID, $notificationURL, $threeDSCompInd) {
			
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSINFO, RESTConstants::$REQUEST_MERCHANT_EMV3DS_AUTHENTICACIONDATA);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION, $protocolVersion);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_ACCEPT_HEADER, $browserAcceptHeader);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_USER_AGENT, $browserUserAgent);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_JAVA_ENABLE, $browserJavaEnable);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_JAVASCRIPT_ENABLE, $browserJavaEnable);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_LANGUAGE, $browserLanguage);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_COLORDEPTH, $browserColorDepth );
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_SCREEN_HEIGHT, $browserScreenHeight );
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_SCREEN_WIDTH, $browserScreenWidth);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_BROWSER_TZ, $browserTZ );
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSSERVERTRANSID, $threeDSServerTransID );
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_NOTIFICATIONURL, $notificationURL);
			$this->addEmvParameter(RESTConstants::$REQUEST_MERCHANT_EMV3DS_THREEDSCOMPIND, $threeDSCompInd);
		}
		
		/** 
		 * Method for set the authentication exemption for V2 EMV3DS
		 * $exemption: constant of the exemption the commerce want to use
		 */	
		public function setExemption($exemption) {
			if (strcmp("LWV", $exemption) == 0) {
				$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_LWV);
			}else if(strcmp( "TRA", $exemption) == 0) {
				$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_TRA);
			}else if(strcmp( "MIT", $exemption) == 0) {
				$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_MIT);
			}else if(strcmp( "COR", $exemption) == 0) {
				$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_COR);
			}else if(strcmp( "ATD", $exemption) == 0) {
				$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_ATD);
			}else if(strcmp( "NDF", $exemption) == 0) {
				$this->addParameter(RESTConstants::$REQUEST_MERCHANT_EXEMPTION, RESTConstants::$REQUEST_MERCHANT_EXEMPTION_VALUE_NDF);
			}	
		}

		public function __toString() {
			$string = "RESTOperationMessage{";
			$string .= 'merchant: ' . $this->getMerchant () . ', ';
			$string .= 'terminal: ' . $this->getTerminal () . ', ';
			$string .= 'order: ' . $this->getOrder () . ', ';
			$string .= 'operID: ' . $this->getOperID () . ', ';
			$string .= 'transactionType: ' . $this->getTransactionType () . ', ';
			$string .= 'currency: ' . $this->getCurrency () . ', ';
			$string .= 'amount: ' . $this->getAmount () . ', ';
			//$string .= 'parameters: ' . json_encode($this->getParameters()) . '';
			if ($this->getEmv() != NULL)
				$string .= 'emv: ' . json_encode($this->getEmv()) . '';
			return $string . "}";
		}
	}
}