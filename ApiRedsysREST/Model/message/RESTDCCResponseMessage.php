<?php
if (! class_exists ( 'RESTResponseMessage' )) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/message/RESTDCCElement.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTResponseInterface.php";
	
	/**
	 * @XML_ELEM=RETORNOXML
	 */
	class RESTDCCResponseMessage extends RESTGenericXml implements RESTResponseInterface {
		private $result;
		
		/**
		 * @XML_ELEM=CODIGO
		 */
		private $apiCode=-1;
		
		/**
		 * @XML_CLASS=RESTDCCElement
		 */
		private $dcc0;
		
		/**
		 * @XML_CLASS=RESTDCCElement
		 */
		private $dcc1;
		
		/**
		 * @XML_ELEM=margenDCC
		 */
		private $dccMargin;
		
		/**
		 * @XML_ELEM=nombreEntidad
		 */
		private $bankName;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_SESION
		 */
		private $sesion;
		public function getResult() {
			return $this->result;
		}
		public function setResult($result) {
			$this->result = $result;
			return $this;
		}
		public function getApiCode() {
			return $this->apiCode;
		}
		public function setApiCode($apiCode) {
			$this->apiCode = $apiCode;
			return $this;
		}
		public function getDcc0() {
			return $this->dcc0;
		}
		public function setDcc0($dcc0) {
			$this->dcc0 = $dcc0;
			return $this;
		}
		public function getDcc1() {
			return $this->dcc1;
		}
		public function setDcc1($dcc1) {
			$this->dcc1 = $dcc1;
			return $this;
		}
		public function getDccMargin() {
			return $this->dccMargin;
		}
		public function setDccMargin($dccMargin) {
			$this->dccMargin = $dccMargin;
			return $this;
		}
		public function getBankName() {
			return $this->bankName;
		}
		public function setBankName($bankName) {
			$this->bankName = $bankName;
			return $this;
		}
		public function getSesion() {
			return $this->sesion;
		}
		public function setSesion($sesion) {
			$this->sesion = $sesion;
			return $this;
		}
		public function __toString() {
			$string = "RESTResponseMessage{";
			$string .= 'result: ' . $this->getResult () . ', ';
			$string .= 'apiCode: ' . $this->getApiCode () . ', ';
			$string .= 'dcc0: ' . $this->getDcc0 () . ', ';
			$string .= 'dcc1: ' . $this->getDcc1 () . ', ';
			$string .= 'dccMargin: ' . $this->getDccMargin () . ', ';
			$string .= 'bankName: ' . $this->getBankName () . ', ';
			$string .= 'sesion: ' . $this->getSesion () . '';
			return $string . "}";
		}
	}
}