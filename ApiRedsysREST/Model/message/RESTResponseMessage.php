<?php
if (! class_exists ( 'RESTResponseMessage' )) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/element/RESTOperationElement.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTResponseInterface.php";
	
	/**
	 * @XML_ELEM=RETORNOXML
	 */
	class RESTResponseMessage extends RESTGenericXml implements RESTResponseInterface {
				
		/**
		 * 	DECLARATION OF VARS OF CLASS: "RESTResponseMessage" 
		 */
		
		 /**
		  * @XML_ELEM=RESULT
		  */
		private $result;
		/**
		 * @XML_ELEM=APICODE
		 */
		private $apiCode;

		/**
		 * @XML_CLASS=RESTOperationElement
		 */
		private $operation;

		/**
		 * GETTERS & SETTERS OF VARS
		 */


		//result
		
		/**
		 * gets the operation's result
		 * return the operation's result
		 */
		public function getResult() {
			return $this->result;
		}

		/**
		 * sets the operation's result
		 * $result: the operation's result
		 */
		public function setResult($result) {
			$this->result = $result;
		}


		//apiCode

		/**
		 * gets the API Code
		 * return the API Code
		 */
		public function getApiCode() {
			return $this->apiCode;
		}
		/**
		 * sets the API Code
		 * $apiCode: API Code
		 */
		public function setApiCode($apiCode) {
			$this->apiCode = $apiCode;
		}


		//operation

		/**
		 * gets operation value
		 * return the operation value
		 */
		public function getOperation() {
			return $this->operation;
		}

		/**
		 * sets the operation value
		 * $operation: operation value 
		 */
		public function setOperation($operation) {
			$this->operation = $operation;
		}

		/**
		 * gets the transaction type
		 * return the transactionType
		 */
		public function getTransactionType(){
			if($this->getOperation() !== NULL)
				return $this->getOperation()->getTransactionType();
			else 
				return NULL;
		}
		
			
		//METHODS

		/** 
		 * Method to get the protocolVersion
		 * return $protocolVersion: 3EMV3DS authentications version
		 */
		public function protocolVersionAnalysis() {
			//To get protocolVersion 
			//InsiteResponseMessage res = (InsiteResponseMessage) response;
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
//			$version3DSecure = $this->getOperation()->getEmv()->get(RESTConstants::$RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION);
//
			$version3DSecure = "";
			if (!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION])) {
				$version3DSecure = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION];
			}
			
			//Protocol Version analysis
			if (strcmp(RESTConstants::$RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION_102, $version3DSecure) == 0) {
				$version3DSecure = "1.0.2";
			}
			
			return $version3DSecure;
		}

		/** 
		 * Method to get the PSD2 result value (inform us if authentication is mandatory)
		 * return $psd2: inform if authenticacion its mandatory (Y/N)
		 */	
		public function PSD2analysis() {
			return $this->getOperation()->getPsd2();
		}
		
		/** 
		 * Method to get the Exemption result value
		 * return $exemption: exemption allowed to the commerce
		 */	
		public function getExemption(){
			return $this->getOperation()->getExemption();
		}

		/** 
		 * Method to get the MD parameter value (protocolVersion 1.0.2)
		 * return $md: protocolVersion 1.0.2 authentication parameter
		 */	
		public function getMDParameter() {
			$md = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if (!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_MD])) {
				$md = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_MD];
			}
			
			return $md;
		}
			
		/** 
		 * Method to get the ACSURL parameter value (protocolVersion 1.0.2)
		 * return $acsURL: protocolVersion 1.0.2 authentication URL
		 */	
		public function getAcsURLParameter() {
			$acsURL = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if(!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_ACSURL])) {
				$acsURL = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_ACSURL];
			}
			
			return $acsURL;
		}
			
		/** 
		 * Method to get the PAREQ parameter value (protocolVersion 1.0.2)
		 * return protocolVersion 1.0.2 authentication parameter
		 */	
		public function getPAReqParameter() {
			$PAReq = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if(!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_PAREQ])) {
				$PAReq = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_PAREQ];
			}

			return $PAReq;
		}
			
		/** 
		 * Method to get the threeDSServerTransID parameter value (protocolVersion 2.X.0)
		 * return protocolVersion 2.X.0 authentication parameter
		 */	
		public function getThreeDSServerTransID() {
			$threeDSServerTransID = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if(!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_THREEDSSERVERTRANSID])) {
				$threeDSServerTransID = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_THREEDSSERVERTRANSID];
			}

			return $threeDSServerTransID;
		}
			
		/** 
		 * Method to get the threeDSInfo parameter value
		 * return Authentication parameter Info for each operation
		 */	
		public function getThreeDSInfo() {
			$threeDSInfo = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if(!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_THREEDSINFO])) {
				$threeDSInfo = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_THREEDSINFO];
			}

			return $threeDSInfo;
		}

			
		/** 
		 * Method to get the threeDSMethodURL parameter value (protocolVersion 2.X.0)
		 * return protocolVersion 2.X.0 authentication URL
		 */	
		public function getThreeDSMethodURL() {
			$threeDSMethod = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if(!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_THREEDSMETHODURL])) {
				$threeDSMethod = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_THREEDSMETHODURL];
			}

			return $threeDSMethod;
		}
				
		/** 
		 * Method to get the CREQ parameter value (protocolVersion 2.X.0)
		 * return protocolVersion 2.X.0 authentication parameter
		 */	
		public function getCreqParameter() {
			$creq = "";
			$emv = $this->getOperation()->getEmv() != null ? json_decode($this->getOperation()->getEmv(), true) : null;
			if(!empty($emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_CREQ])) {
				$creq = $emv[RESTConstants::$RESPONSE_MERCHANT_EMV3DS_CREQ];
			}
			
			return $creq;
		}

		/** 
		 * Method to get Ds_Merchant_Cof_Txnid
		 * @return param
		 */	
		public function getCOFTxnid() {			
			return $this->getOperation()->getCofTxnid();
		}

		/** 
		 * Method to get the DCC Currency
		 * @return dccCurrency: DCC initialRequest Currency
		 */	
		public function getDCCCurrency() {
			$dccCurrency = $this->getOperation()->getDcc()["InfoMonedaTarjeta"]["monedaDCC"];	
		return $dccCurrency;
		}
		
		/** 
		 * Method to get the DCC amount
		 * @return dccAmount: DCC initialRequest amount
		 */	
		public function getDCCAmount() {
			$dccAmount = $this->getOperation()->getDcc()["InfoMonedaTarjeta"]["importeDCC"];
		return $dccAmount;
		}
		
		public function __toString() {
			$string = "RESTResponseMessage{";
			$string .= 'result: ' . $this->getResult () . ', ';
			$string .= 'apiCode: ' . $this->getApiCode() . ',';
			$string .= 'operation: ' . $this->getOperation () . '';
			return $string . "}";
		}	

	}
}