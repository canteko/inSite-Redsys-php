<?php
if (! class_exists ( 'RESTService' )) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTResponseInterface.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/RESTRequestInterface.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Utils/RESTSignatureUtils.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Utils/RESTLogger.php";
	abstract class RESTService {
		
		private $signatureKey;
		private $dispatch = null;
		private $env;
		private $operation;
		private $connected = false;
		private $serviceEndpointURL;
		//preguntar super y lo de enviroment a ME
		function __construct($signatureKey, $env, $operation) {
	
			$this->signatureKey = $signatureKey;
			$this->env = $env;
			$this->operation = $operation;
			$this->serviceEndpointURL = null;
		}
		//No hay que tocar nada
		public function sendOperation($message = false) {
			$result="";
			$post_request = $this->createRequestSOAPMessage($message);
			$header = array (
					"Cache-Control: no-cache",
					"Pragma: no-cache",
					"Content-length: " . strlen ( $post_request ) 
			);
			$url_rs = RESTConstants::getEnviromentEndpoint($this->env, $this->operation);
			
			$rest_do = curl_init ();
			curl_setopt ( $rest_do, CURLOPT_URL, $url_rs );
			curl_setopt ( $rest_do, CURLOPT_CONNECTTIMEOUT, RESTConstants::$CONNECTION_TIMEOUT_VALUE );
			curl_setopt ( $rest_do, CURLOPT_TIMEOUT, RESTConstants::$READ_TIMEOUT_VALUE );
			curl_setopt ( $rest_do, CURLOPT_RETURNTRANSFER, true );
			curl_setopt ( $rest_do, CURLOPT_SSL_VERIFYHOST, 0 );
			curl_setopt ( $rest_do, CURLOPT_SSL_VERIFYPEER, 0 );
			curl_setopt ( $rest_do, CURLOPT_SSLVERSION, RESTConstants::$SSL_TLSv12 );
			curl_setopt ( $rest_do, CURLOPT_POST, true );
			curl_setopt ( $rest_do, CURLOPT_POSTFIELDS, $post_request );
			curl_setopt ( $rest_do, CURLOPT_HTTPHEADER, $header );
			
			RESTLogger::info("Performing request to '".$url_rs."'");
			RESTLogger::debug("Sending JSON ".$post_request);
			$tmp = curl_exec ( $rest_do );
			$httpCode=curl_getinfo($rest_do,CURLINFO_HTTP_CODE);
			
			if($tmp !== false && $httpCode==200){
				$tag = array ();
				$result=$tmp;
			}
			else{
				$strError="Request failure ".(($httpCode!=200)?"[HttpCode: '".$httpCode."']":"").((curl_error($rest_do))?" [Error: '".curl_error($rest_do)."']":"");
				RESTLogger::error($strError);
			}
			
			curl_close( $rest_do );
			return $this->createResponseMessage ( $result );
		}

		public function createRequestSOAPMessage($message) {
			$request=$this->createRequestMessage ( $message );

			$post_request=http_build_query(
								array(
									"Ds_MerchantParameters"=>$request->getDatosEntradaB64(),
									"Ds_SignatureVersion"=>$request->getSignatureVersion(),
									"Ds_Signature"=>$request->getSignature()
								)
						);
			
			RESTLogger::debug("Sending ".RESTLogger::beautifyXML($request->toXml()));

			return $post_request;
		}
		
		public abstract function createRequestMessage($message);
		public abstract function createResponseMessage($trataPeticionResponse);
		public function unMarshallResponseMessage($message){
			$response=new RESTResponseMessage();
			
			$varArray=json_decode($message,true);
			if (array_key_exists ("Ds_MerchantParameters", $varArray) ) {
				$operacion=new RESTOperationElement();
				$operacion->parseJson(base64_decode($varArray["Ds_MerchantParameters"]));
				$operacion->setSignature($varArray["Ds_Signature"]);
				
				$response->setOperation($operacion);
			} else {
				if (array_key_exists ("errorCode", $varArray)) {
					$response->setApiCode($varArray["errorCode"]);
					$response->setResult(RESTConstants::$RESP_LITERAL_KO);
				}
			}
			
			return $response;
		}
		protected function checkSignature($sentData, $remoteSignature) {				
			$calcSignature = RESTSignatureUtils::createMerchantSignatureNotif ( $this->getSignatureKey(), $sentData );
			
			$result = $remoteSignature == $calcSignature;
			if(!$result)
				RESTLogger::error("Signature doesnt match: '".$remoteSignature."' <> '".$calcSignature."'");
			else
				RESTLogger::debug("Signature matches");
			
			return $result;
		}
		public function getSignatureKey() {
			return $this->signatureKey;
		}
		public function getEnv() {
			return $this->env;
		}
		public function __toString() {
			$rc=new ReflectionClass(get_class($this));
			$string = $rc->getName()."{";
			$string .= 'signatureKey: ' . $this->getSignatureKey () . ', ';
			$string .= 'env: ' . $this->getEnv () . '';
			return $string . "}";
		}
	}
}