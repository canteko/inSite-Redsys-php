<?php

include_once 'ApiRedsysREST/initRedsysApi.php';

class Payment {
	// Init variables
	private $order;
	private $amount;
	private $currency;
	private $merchant;
	private $terminal;
	private $transactionType;
	private $cardNumber;
	private $cardExpiryDate;
	private $cvv2;
	private $idOper;
	private $challengeResponseUrl;
	
	// Variables which will be setted during initialization process
	private $protocolVersion;
	private $threeDSServerTransID;
	private $threeDSMethodURL;
	
	// Variables which will be setted for challenge
	// V1
	private $PaReq; 
	private $PaRes;
	private $requestedMD;
	private $receivedMD;
	// V2
	private $CReq;
	private $cres;
	// Both
	private $acsUrl;
		
	// Getters
	public function getOrder() {
		return $this->order;
	}
	public function getAmount() {
		return $this->amount;
	}
	public function getCurrency() {
		return $this->currency;
	}
	public function getMerchant() {
		return $this->merchant;
	}
	public function getTerminal() {
		return $this->terminal;
	}
	public function getTransactionType() {
		return $this->transactionType;
	}
	public function getCardNumber() {
		return $this->cardNumber;
	}
	public function getCardExpiryDate() {
		return $this->cardExpiryDate;
	}
	public function getCvv2() {
		return $this->cvv2;
	}
	public function getIdOper() {
		return $this->idOper;
	}
	public function getChallengeResponseUrl() {
		return $this->challengeResponseUrl;
	}
	public function getProtocolVersion() {
		return $this->protocolVersion;
	}
	public function getThreeDSServerTransID() {
		return $this->threeDSServerTransID;
	}
	public function getThreeDSMethodURL() {
		return $this->threeDSMethodURL;
	}
	public function getPaReq() {
		return $this->PaReq;
	}
	public function getPaRes() {
		return $this->PaRes;
	}
	public function getRequestedMD() {
		return $this->requestedMD;
	}
	public function getReceivedMD() {
		return $this->receivedMD;
	}
	public function getCReq() {
		return $this->CReq;
	}
	public function getCres() {
		return $this->cres;
	}
	public function getAcsUrl() {
		return $this->acsUrl;
	}

	// Setters
	public function setOrder($order) {
		$this->order = $order;
        return $this;
	}
	public function setAmount($amount) {
		$this->amount = $amount;
        return $this;
	}
	public function setCurrency($currency) {
		$this->currency = $currency;
        return $this;
	}
	public function setMerchant($merchant) {
		$this->merchant = $merchant;
        return $this;
	}
	public function setTerminal($terminal) {
		$this->terminal = $terminal;
        return $this;
	}
	public function setTransactionType($transactionType) {
		$this->transactionType = $transactionType;
        return $this;
	}
	public function setCardNumber($cardNumber) {
		$this->cardNumber = $cardNumber;
        return $this;
	}
	public function setCardExpiryDate($cardExpiryDate) {
		$this->cardExpiryDate = $cardExpiryDate;
        return $this;
	}
	public function setCvv2($cvv2) {
		$this->cvv2 = $cvv2;
        return $this;
	}
	public function setIdOper($idOper) {
		$this->idOper = $idOper;
        return $this;
	}
	public function setChallengeResponseUrl($challengeResponseUrl) {
		$this->challengeResponseUrl = $challengeResponseUrl;
        return $this;
	}
	public function setProtocolVersion($protocolVersion) {
		$this->protocolVersion = $protocolVersion;
        return $this;
	}
	public function setThreeDSServerTransID($threeDSServerTransID) {
		$this->threeDSServerTransID = $threeDSServerTransID;
        return $this;
	}
	public function setThreeDSMethodURL($threeDSMethodURL) {
		$this->threeDSMethodURL = $threeDSMethodURL;
        return $this;
	}
	public function setPaReq($paReq) {
		$this->PaReq = $paReq;
        return $this;
	}
	public function setPaRes($paRes) {
		$this->PaRes = $paRes;
        return $this;
	}
	public function setRequestedMD($requestedMD) {
		$this->requestedMD = $requestedMD;
        return $this;
	}
	public function setReceivedMD($receivedMD) {
		$this->receivedMD = $receivedMD;
        return $this;
	}
	public function setCReq($cReq) {
		$this->CReq = $cReq;
        return $this;
	}
	public function setCres($cres) {
		$this->cres = $cres;
        return $this;
	}
	public function setAcsUrl($acsUrl) {
		$this->acsUrl = $acsUrl;
        return $this;
	}
	
	// Methods
	/**
	 * Method for a initial operation request that gives the card protocolVersion
	 */
	public function initialOperation($privateKey) {

        $cardDataInfoRequest = new RESTInitialRequestMessage();

        // Operation mandatory data
        $cardDataInfoRequest->setAmount($this->getAmount()); // i.e. 1,23 (decimal point depends on currency code)
        $cardDataInfoRequest->setCurrency($this->getCurrency()); // ISO-4217 numeric currency code
        $cardDataInfoRequest->setMerchant($this->getMerchant());
        $cardDataInfoRequest->setTerminal($this->getTerminal());
        $cardDataInfoRequest->setOrder($this->getOrder());
        $cardDataInfoRequest->setTransactionType($this->getTransactionType());

        //Card Data information
		if(!empty($this->getIdOper())) {
			$cardDataInfoRequest->setOperID($this->getIdOper());
		} else {
			$cardDataInfoRequest->setCardNumber($this->getCardNumber());
			$cardDataInfoRequest->setCardExpiryDate($this->getCardExpiryDate());
			$cardDataInfoRequest->setCvv2($this->getCvv2());
		}

        //Method to ask about card information data
        $cardDataInfoRequest->demandCardData();

        // Service setting (Signature and Environment)
        $service = new RESTInitialRequestService($privateKey, RESTConstants::$ENV_SANDBOX);

        // Sending the operation to Redsys
        $response = $service->sendOperation($cardDataInfoRequest);
		
		// Setting authentication type
		if(!empty($response) && $response->getResult() != RESTConstants::$RESP_LITERAL_KO) {
			// Protocol version to use
			$this->setProtocolVersion($response->protocolVersionAnalysis());
            // 3DS Transaction ID
			$this->setThreeDSServerTransID($response->getThreeDSServerTransID());
            // 3DS Endpoint
			$this->setThreeDSMethodURL($response->getThreeDSMethodURL());
		}
		
		// Return response
		return $response;
	 }
	
	/**
	 * Method for a authentication operation request. This request depend on the initial request parameter "protocolVersion"
	 */
	public function authenticationOperation($privateKey) {
		
		// Response object for the authenticacionRequest
		$response = null;

		$operationRequest = new RestOperationMessage();
		
		// Operation mandatory data
		$operationRequest->setAmount($this->getAmount()); // i.e. 1,23 (decimal point depends on currency code)
		$operationRequest->setCurrency($this->getCurrency()); // ISO-4217 numeric currency code
		$operationRequest->setMerchant($this->getMerchant());
		$operationRequest->setTerminal($this->getTerminal());
		$operationRequest->setOrder($this->getOrder());
		$operationRequest->setTransactionType($this->getTransactionType());

		//Card Data information
		if (!empty($this->getIdOper())) {
			$operationRequest->setOperID($this->getIdOper());
		} else {
			$operationRequest->setCardNumber($this->getCardNumber());
			$operationRequest->setCardExpiryDate($this->getCardExpiryDate());
			$operationRequest->setCvv2($this->getCvv2());
		}

		if ($this->getProtocolVersion() == RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
			//Method to make a authenticationRequest with protocolVersion 1.0.2
			$operationRequest->setEMV3DSParamsV1();			
		} else {
			//Method to make a authenticationRequest with protocolVersion 2.X.0
			$browserAcceptHeader = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8,application/json";
			$browserUserAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36";
			$browserJavaEnable = "false";
			$browserJavaScriptEnabled = "false";
			$browserLanguage = "ES-es";
			$browserColorDepth = "24";
			$browserScreenHeight = "1250";
			$browserScreenWidth = "1320";
			$browserTZ = "52";
			//threeDSServerTransID = "8de84430-3336-4ff4-b18d-f073b546ccea";
			$notificationURL= $this->getChallengeResponseUrl();
			$threeDSCompInd = "Y";
			
			//Method that can be use to add the return parameters to the authentication request for protocolVersion 2.X.0
			$operationRequest->setEMV3DSParamsV2($this->getProtocolVersion(), $browserAcceptHeader, $browserUserAgent, $browserJavaEnable, $browserJavaScriptEnabled, $browserLanguage, $browserColorDepth, $browserScreenHeight, $browserScreenWidth, $browserTZ, $this->getThreeDSServerTransID(), $notificationURL, $threeDSCompInd);
		}
	
		try {
			// Service setting (Signature and Environment)
			$service = new RestOperationService($privateKey, RESTConstants::$ENV_SANDBOX);
			//Send the operation and catch the response
			$response = $service->sendOperation($operationRequest);
			// Response analysis
		} catch (Exception $e) {
			// Error treatment
			var_dump($e->getMessage());
		}
		
		// If response is null, there's nothing else to do
		if(empty($response)) {
			return $response;
		}
		
		// We get protocolVersion just in case it's changed and save its parameters
		if($response->getResult() == RESTConstants::$RESP_LITERAL_AUT) {
			$protocolVersion = $response->protocolVersionAnalysis();
			$this->setProtocolVersion($protocolVersion);
			
			// Check what parameters we need to save according to the protocolVersion
			$this->setAcsUrl($response->getAcsURLParameter());
			if(RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102 == $this->getProtocolVersion()) {
				$this->setRequestedMD($response->getMDParameter());
				$this->setPaReq($response->getPAReqParameter());
			} else {
				$this->setCReq($response->getCreqParameter());
			}
		}
		
		// Return response
		return $response;
	}
	
	/**
	 * Method to launch a challenge
	 */
	public function challenge() {
		$form = "<form action='" . $this->getAcsUrl() . "' class='round-border' method='POST' enctype='application/x-www-form-urlencoded' target='_blank'>";

		// Adding parameters we need to save send depending on the protocolVersion
        if($this->getProtocolVersion() == RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
            $form .= "<input type='text' name='PaReq' value='" . $this->getPaReq() . "'><br>"
            	 . "<input type='text' name='MD' value='" . $this->getRequestedMD() . "'><br>"
            	 . "<input type='text' name='TermUrl' value='" . $this->getChallengeResponseUrl() . "'><br>";
        } else {
        	$form .= "<input type='text' name='CReq' value='" . $this->getCReq() . "'><br>";
        }

        // Submit
        $form .= "<input type='submit' style='padding: 5px; background-color: #ffc65c' value='Realizar pago'></form>";

        // Return form
        return $form;
	}
	
	/**
	 * Method to receive the challenge response
	 */
	public function challengeResponse($request, $privateKey) {
		// Operation mandatory data
		$challengeRequest = new RestAuthenticationRequestMessage();

		// Operation mandatory data
		$challengeRequest->setAmount($this->getAmount()); // i.e. 1,23 (decimal point depends on currency code)
		$challengeRequest->setCurrency($this->getCurrency()); // ISO-4217 numeric currency code
		$challengeRequest->setMerchant($this->getMerchant());
		$challengeRequest->setTerminal($this->getTerminal());
		$challengeRequest->setOrder($this->getOrder());
		$challengeRequest->setTransactionType($this->getTransactionType());

		//Card Data information
		if (!empty($this->getIdOper())) {
			$challengeRequest->setOperID($this->getIdOper());
		} else {
			$challengeRequest->setCardNumber($this->getCardNumber());
			$challengeRequest->setCardExpiryDate($this->getCardExpiryDate());
			$challengeRequest->setCvv2($this->getCvv2());
		}

        // Receiving parameters we need to save send depending on the protocolVersion
        if($this->getProtocolVersion() == RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
        	// Gathering params
           	$pares = $request["PaRes"];
			$md = $request["MD"];
        	
        	// Setting received params into class
        	$this->setPaRes($pares);
            $this->setReceivedMD($md);
            
            // Setting params into request
            $challengeRequest->challengeRequestV1($pares, $md);
        } else {
        	// Gathering params
           	$cres = $request["cres"];
        	
        	// Setting received params into class
        	$this->setCres($cres);
        	
        	// Setting params into request
        	$challengeRequest->challengeRequestV2($this->getProtocolVersion(), $cres);
        }
        
		// Response object for the cardData Request
		$response = null;
		try {
			// Service setting (Signature and Environment)
			$service = new RestAuthenticationRequestService($privateKey, RESTConstants::$ENV_SANDBOX);
			//Send the operation and catch the response
			$response = $service->sendOperation($challengeRequest);
			// Response analysis
		} catch (Exception $e) {
			// Error treatment
			var_dump($e->getMessage());
		}
		
		// If result is OK, this operation is finished
		if(!empty($response) && $response->getResult() == RESTConstants::$RESP_LITERAL_OK) {		
			$this->setIsFinished(true);
		}
		
		// Return response
		return $response;
	}

	public function toArray() {
		$array = array(
			"order" => $this->order,
			"amount" => $this->amount,
			"currency" => $this->currency,
			"merchant" => $this->merchant,
			"terminal" => $this->terminal,
			"transactionType" => $this->transactionType,
			"cardNumber" => $this->cardNumber,
			"cardExpiryDate" => $this->cardExpiryDate,
			"cvv2" => $this->cvv2,
			"idOper" => $this->idOper,
			"challengeResponseUrl" => $this->challengeResponseUrl,

			// Variables which will be setted during initialization process
			"protocolVersion" => $this->protocolVersion,
			"threeDSServerTransID" => $this->threeDSServerTransID,
			"threeDSMethodURL" => $this->threeDSMethodURL,

			// Variables which will be setted for challenge
			// V1
			"PaReq" => $this->PaReq,
			"PaRes" => $this->PaRes,
			"requestedMD" => $this->requestedMD,
			"receivedMD" => $this->receivedMD,
			// V2
			"CReq" => $this->CReq,
			"cres" => $this->cres,
			// Both
			"acsUrl" => $this->acsUrl,

			// Status variables
			"isInitialized" => $this->isInitialized,
			"isFinished" => $this->isFinished,
		);

		return $array;
	}

	public static function newWithParams($order, $amount, $currency, $merchant, $terminal, $transactionType, $cardNumber, $cardExpiryDate, $cvv2, $idOper, $challengeResponseUrl) {
		$payment = new Payment();
		
		// Parámetros que van en la petición
		$payment->setOrder($order);
		$payment->setAmount($amount);
		$payment->setCurrency($currency);
		$payment->setMerchant($merchant);
		$payment->setTerminal($terminal);
		$payment->setTransactionType($transactionType);

		// Tarjeta
		$payment->setCardNumber($cardNumber);
		$payment->setCardExpiryDate($cardExpiryDate);
		$payment->setCvv2($cvv2);

		// IDOper
		$payment->setIdOper($idOper);

		// URL para recibir la respuesta del challenge
		$payment->setChallengeResponseUrl($challengeResponseUrl);

		// Variables que no se reciben al inicializar el pago
		$payment->setProtocolVersion(null);
		$payment->setThreeDSServerTransID(null);
		$payment->setThreeDSMethodURL(null);
		$payment->setPaReq(null);
		$payment->setPaRes(null);
		$payment->setRequestedMD(null);
		$payment->setReceivedMD(null);
		$payment->setCReq(null);
		$payment->setCres(null);
		$payment->setAcsUrl(null);
		$payment->setInitialized(false);
		$payment->setFinished(false);

		return $payment;
	}
}
?>