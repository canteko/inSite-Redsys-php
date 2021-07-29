<?php
if (! class_exists ( 'RESTDCCConfirmationMessage' )) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTGenericXml.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTRequestInterface.php";
	
	/**
	 * @XML_ELEM=DATOSENTRADA
	 */
	class RESTDCCConfirmationMessage extends RESTGenericXml implements RESTRequestInterface {
		
		/**
		 * @XML_ELEM=DS_MERCHANT_ORDER
		 */
		private $order = null;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_MERCHANTCODE
		 */
		private $merchant = null;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_TERMINAL
		 */
		private $terminal = null;
		
		/**
		 * @XML_ELEM=Sis_Divisa
		 */
		private $currencyCode = null;
		
		/**
		 * @XML_ELEM=DS_MERCHANT_SESION
		 */
		private $sesion = null;
		public function getOrder() {
			return $this->order;
		}
		public function setOrder($order) {
			$this->order = $order;
			return $this;
		}
		public function getMerchant() {
			return $this->merchant;
		}
		public function setMerchant($merchant) {
			$this->merchant = $merchant;
			return $this;
		}
		public function getTerminal() {
			return $this->terminal;
		}
		public function setTerminal($terminal) {
			$this->terminal = $terminal;
			return $this;
		}
		public function getCurrencyCode() {
			return $this->currencyCode;
		}
		public function setCurrencyCode($currency, $amount) {
			$this->currencyCode = $currency . "#" . $amount;
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
			$string = "RESTDCCConfirmationMessage{";
			$string .= 'order: ' . $this->getOrder () . ', ';
			$string .= 'merchant: ' . $this->getMerchant () . ', ';
			$string .= 'terminal: ' . $this->getTerminal () . ', ';
			$string .= 'currencyCode: ' . $this->getCurrencyCode () . ', ';
			$string .= 'sesion: ' . $this->getSesion () . '';
			return $string . "}";
		}
	}
}