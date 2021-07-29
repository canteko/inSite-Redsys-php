<?php

if (!class_exists('RESTOperationService')) {
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Service/RESTService.php";
	//include_once $GLOBALS["REDSYS_API_PATH"]."/Service/Impl/RESTDCCConfirmationService.php";
	//include_once $GLOBALS["REDSYS_API_PATH"]."/Model/message/RESTDCCConfirmationMessage.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/element/RESTOperationElement.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/message/RESTResponseMessage.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Model/message/RESTInitialRequestMessage.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Utils/RESTSignatureUtils.php";
	include_once $GLOBALS["REDSYS_API_PATH"] . "/Utils/RESTLogger.php";

	class RESTOperationService extends RESTService
	{
		private $request;
		function __construct($signatureKey, $env)
		{
			parent::__construct($signatureKey, $env, RESTConstants::$TRATA);
		}

		public function createRequestMessage($message)
		{
			$this->request = $message;
			$req = new RESTInitialRequestMessage();
			$req->setDatosEntrada($message);

			$signatureUtils = new RESTSignatureUtils();
			$localSignature = $signatureUtils->createMerchantSignature($this->getSignatureKey(), $req->getDatosEntradaB64());

			$req->setSignature($localSignature);

			return $req;
		}

		public function createResponseMessage($trataPeticionResponse)
		{
			$response = new RESTResponseMessage();
			$varArray = json_decode($trataPeticionResponse, true);

			if (isset($varArray["ERROR"]) || isset($varArray["errorCode"])) {

				if ($varArray["errorCode"] == "SIS0432") {

					RESTLogger::error("Los datos de la operación se han perdido o no se han enviao correctamente. --- " .$varArray["errorCode"]);
					$response->setResult(RESTConstants::$RESP_LITERAL_KO);

				} else {

					RESTLogger::error("Received JSON '" . $trataPeticionResponse . "'");
					$response->setResult(RESTConstants::$RESP_LITERAL_KO);
				}
				
			} else {
				$varArray = json_decode(base64_decode($varArray["Ds_MerchantParameters"]), true);

				$dccElem = isset($varArray[RESTConstants::$RESPONSE_DCC_MARGIN_TAG]);

				if ($dccElem) {
					// 					$dccService=new RESTDCCConfirmationService($this->getSignatureKey(), $this->getEnv());
					// 					$dccResponse=$dccService->unMarshallResponseMessage($trataPeticionResponse);
					// 					RESTLogger::debug("Received ".RESTLogger::beautifyXML($dccResponse->toXml()));

					// 					$dccConfirmation=new RESTDCCConfirmationMessage();
					// 					$currency="";
					// 					$amount="";
					// 					if($this->request->isDcc()){
					// 						$currency=$dccResponse->getDcc0()->getCurrency();
					// 						$amount=$dccResponse->getDcc0()->getAmount();
					// 					}
					// 					else{
					// 						$currency=$dccResponse->getDcc1()->getCurrency();
					// 						$amount=$dccResponse->getDcc1()->getAmount();
					// 					}

					// 					$dccConfirmation->setCurrencyCode($currency, $amount);
					// 					$dccConfirmation->setMerchant($this->request->getMerchant());
					// 					$dccConfirmation->setTerminal($this->request->getTerminal());
					// 					$dccConfirmation->setOrder($this->request->getOrder());
					// 					$dccConfirmation->setSesion($dccResponse->getSesion());

					// 					$response=$dccService->sendOperation($dccConfirmation);
				} else {
					$response = $this->unMarshallResponseMessage($trataPeticionResponse);
					if (is_null($response->getApiCode())) {
						$paramsB64 = json_decode($trataPeticionResponse, true)["Ds_MerchantParameters"];
						$response->setApiCode($response->getOperation()->getResponseCode());

						if (!$this->checkSignature($paramsB64, $response->getOperation()->getSignature())) {
							$response->setResult(RESTConstants::$RESP_LITERAL_KO);
						} else {
							if ($response->getOperation()->requiresSCA()) {
								$response->setResult(RESTConstants::$RESP_LITERAL_AUT);
							} else {
								$transType = $response->getTransactionType();
								switch ((int)$response->getOperation()->getResponseCode()) {
									case RESTConstants::$AUTHORIZATION_OK:
										$response->setResult(($transType == RESTConstants::$AUTHORIZATION || $transType == RESTConstants::$PREAUTHORIZATION) ? RESTConstants::$RESP_LITERAL_OK : RESTConstants::$RESP_LITERAL_KO);
										break;
									case RESTConstants::$CONFIRMATION_OK:
										$response->setResult(($transType == RESTConstants::$CONFIRMATION || $transType == RESTConstants::$REFUND) ? RESTConstants::$RESP_LITERAL_OK : RESTConstants::$RESP_LITERAL_KO);
										break;
									case RESTConstants::$CANCELLATION_OK:
										$response->setResult($transType == RESTConstants::$CANCELLATION ? RESTConstants::$RESP_LITERAL_OK : RESTConstants::$RESP_LITERAL_KO);
										break;
									default:
										$response->setResult(RESTConstants::$RESP_LITERAL_KO);
								}
							}
						}

					}
					
				}

				RESTLogger::debug("Received " . RESTLogger::beautifyXML($response->toXml()));

				if ($response->getResult() == RESTConstants::$RESP_LITERAL_OK) {
					RESTLogger::info("Operation finished successfully");
				} else {
					if ($response->getResult() == RESTConstants::$RESP_LITERAL_AUT) {
						RESTLogger::info("Operation requires autentication");
					} else {
						RESTLogger::info("Operation finished with errors");
					}
				}
			}
			return $response;
		}

		// public function unMarshallResponseMessage($message){
		// 	$response=new RESTResponseMessage();

		// 	$varArray=json_decode($message,true);

		// 	$operacion=new RESTOperationElement();
		// 	$operacion->parseJson(base64_decode($varArray["Ds_MerchantParameters"]));
		// 	$operacion->setSignature($varArray["Ds_Signature"]);

		// 	$response->setOperation($operacion);

		// 	return $response;
		// }
	}
}
