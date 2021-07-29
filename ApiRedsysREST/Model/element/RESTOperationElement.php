<?php
if (! class_exists ( 'RESTOperationElement' )) {
	include_once $GLOBALS ["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	
	/**
	 * @XML_ELEM=OPERACION
	 */
	class RESTOperationElement extends RESTGenericXml {

		/**
		 * 	DECLARATION OF VARS OF CLASS: "RESTOperationElement" 
		*/


		/**
		 * @XML_ELEM=Ds_Amount
		 */
		private $amount;
		
		/**
		 * @XML_ELEM=Ds_Currency
		 */
		private $currency;
		
		/**
		 * @XML_ELEM=Ds_Order
		 */
		private $order;
		
		/**
		 * @XML_ELEM=Ds_Signature
		 */
		private $signature;
		
		/**
		 * @XML_ELEM=Ds_MerchantCode
		 */
		private $merchant;
		
		/**
		 * @XML_ELEM=Ds_Terminal
		 */
		private $terminal;
		
		/**
		 * @XML_ELEM=Ds_Response
		 */
		private $responseCode;
		
		/**
		 * @XML_ELEM=Ds_AuthorisationCode
		 */
		private $authCode;
		
		/**
		 * @XML_ELEM=Ds_TransactionType
		 */
		private $transactionType;
		
		/**
		 * @XML_ELEM=Ds_SecurePayment
		 */
		private $securePayment;
		
		/**
		 * @XML_ELEM=Ds_Language
		 */
		private $language;
		
		/**
		 * @XML_ELEM=Ds_MerchantData
		 */
		private $merchantData;
		
		/**
		 * @XML_ELEM=Ds_Card_Country
		 */
		private $cardCountry;
		
		/**
		 * @XML_ELEM=Ds_CardNumber
		 */
		private $cardNumber;
		
		/**
		 * @XML_ELEM=Ds_Card_Brand
		 */
		private $cardBrand;

		
		/**
		 * @XML_ELEM=Ds_Card_Type
		 */
		private $cardType;
		
		/**
		 * @XML_ELEM=Ds_ExpiryDate
		 */
		private $expiryDate;
		
		/**
		 * @XML_ELEM=Ds_Merchant_Identifier
		 */
		private $merchantIdentifier;
		
		/**
		 * @XML_ELEM=Ds_Card_PSD2
		 */
		private $psd2;
	
		/**
		 * @XML_ELEM=Ds_Excep_SCA
		 */
		private $exemption;
	
		/**
		 * @XML_ELEM=Ds_Merchant_Cof_Txnid
		 */
		private $cofTxnid;		
		
		/**
		 * @XML_ELEM=Ds_EMV3DS
		 */
		private $emv;
		
		/**
		 * @XML_ELEM=Ds_DCC
		 */
		private $dcc;
		/*
		 * @XML ELEM=InfoMonedaTarjeta
		 */
		private $infoMonedaTarjeta;

		private  $acsURL = "";
		private $protocolVersion;
		private $threeDSInfo;


		/**
		 * GETTERS & SETTERS OF VARS
		*/

		//amount
		public function getAmount() {
			return $this->amount;
		}
		public function setAmount($amount) {
			$this->amount = $amount;
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

		//order
		public function getOrder() {
			return $this->order;
		}
		public function setOrder($order) {
			$this->order = $order;
			return $this;
		}

		//signature
		public function getSignature() {
			return $this->signature;
		}
		public function setSignature($signature) {
			$this->signature = $signature;
			return $this;
		}
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

		//responseCode
		public function getResponseCode() {
			return $this->responseCode;
		}
		public function setResponseCode($responseCode) {
			$this->responseCode = $responseCode;
			return $this;
		}

		//authCode
		public function getAuthCode() {
			return $this->authCode;
		}
		public function setAuthCode($authCode) {
			$this->authCode = $authCode;
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

		//securePayment
		public function getSecurePayment() {
			return $this->securePayment;
		}
		public function setSecurePayment($securePayment) {
			$this->securePayment = $securePayment;
			return $this;
		}

		//language
		public function getLanguage() {
			return $this->language;
		}
		public function setLanguage($language) {
			$this->language = $language;
			return $this;
		}

		//merchantData
		public function getMerchantData() {
			return $this->merchantData;
		}
		public function setMerchantData($merchantData) {
			$this->merchantData = $merchantData;
			return $this;
		}

		//cardCountry
		public function getCardCountry() {
			return $this->cardCountry;
		}
		public function setCardCountry($cardCountry) {
			$this->cardCountry = $cardCountry;
			return $this;
		}

		//cardNumber
		public function getCardNumber() {
			return $this->cardNumber;
		}
		public function setCardNumber($cardNumber) {
			$this->cardNumber = $cardNumber;
			return $this;
		}

		//cardBrand
		public function getCardBrand(){
			return $this->cardBrand;
		}
		public function setCardBrand($cardBrand){
			$this->cardBrand = $cardBrand;
			return $this;
		}

		//cardType
		public function getCardType(){
			return $this->cardType;
		}
		public function setCardType($cardType){
			$this->cardType = $cardType;
			return $this;
		}

		//expiryDate
		public function getExpiryDate() {
			return $this->expiryDate;
		}
		public function setExpiryDate($expiryDate) {
			$this->expiryDate = $expiryDate;
			return $this;
		}

		//merchantIdentifier
		public function getMerchantIdentifier() {
			return $this->merchantIdentifier;
		}
		public function setMerchantIdentifier($merchantIdentifier) {
			$this->merchantIdentifier = $merchantIdentifier;
			return $this;
		}
		
		//psd2
		public function getPsd2() {
			return $this->psd2;
		}
		
		public function setPsd2($psd2) {
			$this->psd2 = $psd2;
			return $this;
		}
		
		//exemption
		public function getExemption() {
			return $this->exemption;
		}
		
		public function setExemption($exemption) {
			$this->exemption = $exemption;
			return $this;
		}

		//cof
		public function getCof() {
			return $this->cof;
		}
		
		public function setCof($cof) {
			$this->cof = $cof;
			return $this;
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

		//cofTxnid
		public function getCofTxnid() {
			return $this->cofTxnid;
		}
		public function setCofTxnid($cofTxnid) {
			$this->cofTxnid = $cofTxnid;
		}
	
		//dcc
		public function getDcc() {
			return $this->dcc;
		}
		public function setDcc($dcc) {
			$this->dcc = $dcc;
		}

		//infoMonedaTarjeta
		public function getInfoMonedaTarjeta() {
			return $this->infoMonedaTarjeta;
		}
		public function setInfoMonedaTarjeta($infoMonedaTarjeta) {
			$this->infoMonedaTarjeta = $infoMonedaTarjeta;
		}

		//threeDSInfo
		public function getThreeDSInfo() {
			$val=null;
					
			if($this->emv!=null && array_key_exists(RESTConstants::$RESPONSE_JSON_THREEDSINFO_ENTRY, $this->emv)){
				$val=$this->emv[RESTConstants::$RESPONSE_JSON_THREEDSINFO_ENTRY];
			}
					
			return $val;
		}
			
		public function setThreeDSInfo( $threeDSInfo_value) {
			$threeDSInfo = $threeDSInfo_value;
		}
				
		//protocolVersion
		public function getProtocolVersion() {
			$val=null;
			
			if($this->emv!=null && array_key_exists(RESTConstants::$RESPONSE_JSON_PROTOCOL_VERSION_ENTRY, $this->emv)){
				$val=$this->emv[RESTConstants::$RESPONSE_JSON_PROTOCOL_VERSION_ENTRY];
			}			
			return $val;
		}
		
		public function setProtocolVersion($protocolVersion_value) {
			$protocolVersion = $protocolVersion_value;		
		}

		////////////////////////////////////////////////

		//ESTAS FUNCIONES NO VIENEN EN JAVA
		
		public function getAcsUrl() {
			$val=null;

			if($this->emv!=null && array_key_exists(RESTConstants::$RESPONSE_JSON_ACS_ENTRY, $this->emv)){
				$val=$this->emv[RESTConstants::$RESPONSE_JSON_ACS_ENTRY];
			}
			
			return $val;
		}

		public function getPaRequest() {
			$val=null;
			
			if($this->emv!=null && array_key_exists(RESTConstants::$RESPONSE_JSON_PAREQ_ENTRY, $this->emv)){
					$val=$this->emv[RESTConstants::$RESPONSE_JSON_PAREQ_ENTRY];
			}
			
			return $val;
		}
		public function getAutSession() {
			$val=null;
			
			if($this->emv!=null && array_key_exists(RESTConstants::$RESPONSE_JSON_MD_ENTRY, $this->emv)){
				$val=$this->emv[RESTConstants::$RESPONSE_JSON_MD_ENTRY];
			}
			
			return $val;
		}
		
		public function requires3DS1(){
			return $this->getThreeDSInfo()==RESTConstants::$RESPONSE_3DS_CHALLENGE_REQUEST 
				&& $this->getProtocolVersion()==RESTConstants::$RESPONSE_3DS_VERSION_1; 
		}
		
		public function requires3DS2(){
			return $this->getThreeDSInfo()==RESTConstants::$RESPONSE_3DS_CHALLENGE_REQUEST 
				&& ($this->getProtocolVersion()!=NULL && strpos($this->getProtocolVersion(), RESTConstants::$RESPONSE_3DS_VERSION_2_PREFIX) === 0); 
		}

		////////////////////////////////////////////////

		/** Method to get the PSD2 result value (inform us if authentication is mandatory)
		 *  $psd2: inform if authenticacion its mandatory (Y/N)
		 */
		public function isPSD2(){
			$required = false;
			try {
				$psd2 = $this->getPsd2();
				if (RESTConstants::$RESPONSE_PSD2_TRUE == $psd2) {
					$required = true;
				}
			} catch (Exception $e) {
				$this->setExemption($e);
			}
			
			return $required;
		}
		
		/** Method that inform if neeeds authentication
		 * $acsURL: authenticacion URL, if needs authentication, the virtual TPV return the authentication URL
		*/
		public function requiresSCA() {
			$required = false;
			try {
				$acsURL = $this->getAcsUrl();
				if (strlen($acsURL) > 10) {
					$required = true;
				}
			} catch (Exception $e) {
				$this->setExemption($e);
			}
			return $required;
		}
		
		
		public function __toString() {
			$string = "RESTOperationElement{";
			$string .= 'amount: ' . $this->getAmount () . ', ';
			$string .= 'currency: ' . $this->getCurrency () . ', ';
			$string .= 'order: ' . $this->getOrder () . ', ';
			$string .= 'signature: ' . $this->getSignature () . ', ';
			$string .= 'merchant: ' . $this->getMerchant () . ', ';
			$string .= 'terminal: ' . $this->getTerminal () . ', ';
			$string .= 'responseCode: ' . $this->getResponseCode () . ', ';
			$string .= 'authCode: ' . $this->getAuthCode () . ', ';
			$string .= 'transactionType: ' . $this->getTransactionType () . ', ';
			$string .= 'securePayment: ' . $this->getSecurePayment () . ', ';
			$string .= 'language: ' . $this->getLanguage () . ', ';
			$string .= 'merchantData: ' . $this->getMerchantData () . ', ';
			$string .= 'cardCountry: ' . $this->getCardCountry () . ', ';
			$string .= 'cardNumber: ' . $this->getCardNumber () . ', ';
			$string .= 'expiryDate: ' . $this->getExpiryDate () . ', ';
			$string .= 'merchantIdentifier: ' . $this->getMerchantIdentifier () . ', ';
			$string .= 'dcc: ' . $this->getDcc() . ', ';
			$string .= 'emv: ' . $this->getEmv () . ', ';
			return $string . "}";
		}
	}

}
