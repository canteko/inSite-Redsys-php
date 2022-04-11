<?php

include_once '../ApiRedsysREST/initRedsysApi.php';
include_once 'Config.php';

function main($request)
{
    // Settings variables received on request
    // Environment
    $environment = (!empty($request['env']) ? $request['env'] : RESTConstants::$ENV_SANDBOX);

    // privateKey has to be obtained from your system and not directly written into code, this is just an example coded to be functional out of the box
    $privateKey = ($environment == RESTConstants::$ENV_SANDBOX ? Config::$PRIVATEKEY_SANDBOX : Config::$PRIVATEKEY_PROD);

    // idOper
    $idOper = (!empty($request['idOper']) ? $request['idOper'] : "");

    // Currency ID (ISO 4217)​
    $currency = (!empty($request['currency']) ? $request['currency'] : "");

    // merchantId and terminal have to be obtained from your system and not directly written into code no received from post, this is just an example coded to be functional out of the box
    $merchant = (!empty($request['merchantId']) ? $request['merchantId'] : "999008881");
    $terminal = (!empty($request['terminal']) ? $request['terminal'] : "1");

    // Transaction type is authorization
    $transactionType = RESTConstants::$AUTHORIZATION;

    // Value of the purchase
    // This value has to be VALUE*100, if we want to pay 1.99€, we have to send 199, so we multiply original amount by 100
    $amountRaw = str_replace(',', '.', (!empty($request['amount']) ? $request['amount'] : "1.99"));
    $amount = strval(floatval($amountRaw) * 100);

    // Transaction ID
    $order = (!empty($request['merchantOrderId']) ? $request['merchantOrderId'] : time());

    // Getting host URL
    $explodeUrl = explode('/', $_SERVER["HTTP_REFERER"]);
    $host = str_replace($explodeUrl[count($explodeUrl) - 1], '', $_SERVER["HTTP_REFERER"]);

    // Challenge Response URL
    // These variables should be stored on your system, not to be obtained from an url request, it's just a code to be functional out of the box
    $challengeResponseUrl = "$host" . "paymentBackend.php?"
        . "&merchantOrderId=$order"
        . "&idOper=$idOper"
        . "&currency=$currency"
        . "&merchantId=$merchant"
        . "&terminal=$terminal"
        . "&env=$environment"
        . "&amount=$amount";

    // Variables needed
    $protocolVersion = $threeDSServerTransID = $threeDSMethodURL = "";

    // Initial operation
    $ioResponse = initialOperation($privateKey, $order, $amount, $currency, $merchant, $terminal, $transactionType, $idOper, $environment);

    // Check response
    if (empty($ioResponse) || $ioResponse->getResult() == RESTConstants::$RESP_LITERAL_KO) {
        return $ioResponse;
    }

    // Protocol version to use
    $protocolVersion = $ioResponse->protocolVersionAnalysis();
    // 3DS Transaction ID
    $threeDSServerTransID = $ioResponse->getThreeDSServerTransID();
    // 3DS Endpoint
    $threeDSMethodURL = $ioResponse->getThreeDSMethodURL();
    // ThreeDSCompInd, hardcoded value to make it work out of the box, check line 71
    $threeDSCompInd = 'Y';

    // Check if we have to do 3DSMETHOD
    if(empty($threeDSMethodURL)){
        $threeDSCompInd = 'N'; 
    } else {
        // TODO We would need to do 3DSMETHOD if URL is not empty
    }

    // BrowserData
    $browserData = array();
    if($protocolVersion != RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
        $browserData['javaEnabled'] = (!empty($request['javaEnabled']) ? $request['javaEnabled'] : "false");
        $browserData['javascriptEnabled'] = (!empty($request['javascriptEnabled']) ? $request['javascriptEnabled'] : "false");
        $browserData['browserLanguage'] = (!empty($request['browserLanguage']) ? $request['browserLanguage'] : "");
        $browserData['browserColorDepth'] = (!empty($request['browserColorDepth']) ? $request['browserColorDepth'] : "");
        $browserData['browserScreenHeight'] = (!empty($request['browserScreenHeight']) ? $request['browserScreenHeight'] : "");
        $browserData['browserScreenWidth'] = (!empty($request['browserScreenWidth']) ? $request['browserScreenWidth'] : "");
        $browserData['browserTZ'] = (!empty($request['browserTZ']) ? $request['browserTZ'] : "");
    }

    $toResponse = null;
    // If response is AUT, we need authentication,
    // If response is OK, initialization is OK and we do not need authentication, but authentication is available anyway
    // If response is KO, something went wrong
    // In order to pay securely, we are going to use authentication despite of response (If it is not a KO)
    if ($ioResponse->getResult() == RESTConstants::$RESP_LITERAL_AUT || $ioResponse->getResult() == RESTConstants::$RESP_LITERAL_OK) {
        $toResponse = authenticationOperation($privateKey, $order, $amount, $currency, $merchant, $terminal, $transactionType, $idOper, $environment, $protocolVersion, $threeDSCompInd, $browserData, $threeDSServerTransID, $challengeResponseUrl);
    }

    // Challenge variables
    $acsURL = $md = $pareq = $creq = "";
    // We get protocolVersion just in case it's changed and save its parameters if we need authentication
    if ($toResponse->getResult() == RESTConstants::$RESP_LITERAL_AUT) {
        $protocolVersion = $toResponse->protocolVersionAnalysis();

        // Check what parameters we need to save according to the protocolVersion
        $acsURL = $toResponse->getAcsURLParameter();
        if (RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102 == $protocolVersion) {
            $md = $toResponse->getMDParameter();
            $pareq = $toResponse->getPAReqParameter();
        } else {
            $creq = $toResponse->getCreqParameter();
        }
        // We print our challenge
        echo (challenge($protocolVersion, $acsURL, $md, $pareq, $challengeResponseUrl, $creq));

    }

    return $toResponse;
}

function initialOperation($privateKey, $order, $amount, $currency, $merchant, $terminal, $transactionType, $idOper, $environment)
{
    $cardDataInfoRequest = new RESTInitialRequestMessage();

    // Operation mandatory data
    $cardDataInfoRequest->setAmount($amount); // i.e. 1,23 (decimal point depends on currency code)
    $cardDataInfoRequest->setCurrency($currency); // ISO-4217 numeric currency code
    $cardDataInfoRequest->setMerchant($merchant);
    $cardDataInfoRequest->setTerminal($terminal);
    $cardDataInfoRequest->setOrder($order);
    $cardDataInfoRequest->setTransactionType($transactionType);

    // IDOper
    $cardDataInfoRequest->setOperID($idOper);

    //Method to ask about card information data
    $cardDataInfoRequest->demandCardData();

    // Service setting (Signature and Environment)
    $service = new RESTInitialRequestService($privateKey, $environment);

    // Sending the operation to Redsys
    $response = $service->sendOperation($cardDataInfoRequest);

    // Return response
    return $response;
}

/**
 * Method for a authentication operation request. This request depend on the initial request parameter "protocolVersion"
 */
function authenticationOperation($privateKey, $order, $amount, $currency, $merchant, $terminal, $transactionType, $idOper, $environment, $protocolVersion, $threeDSCompInd = "N", $browserData = null, $threeDSServerTransID = "", $challengeResponseUrl = "")
{
    $operationRequest = new RestOperationMessage();

    // Operation mandatory data
    $operationRequest->setAmount($amount); // i.e. 1,23 (decimal point depends on currency code)
    $operationRequest->setCurrency($currency); // ISO-4217 numeric currency code
    $operationRequest->setMerchant($merchant);
    $operationRequest->setTerminal($terminal);
    $operationRequest->setOrder($order);
    $operationRequest->setTransactionType($transactionType);
    $operationRequest->setOperID($idOper);

    if ($protocolVersion == RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
        // Method to make an authenticationRequest with protocolVersion 1.0.2
        $operationRequest->setEMV3DSParamsV1();
    } else {
        $browserJavaEnable =  $browserJavaScriptEnabled =  $browserLanguage =  $browserColorDepth =  $browserScreenHeight =  $browserScreenWidth =  $browserTZ = "";

        // Method to make an authenticationRequest with protocolVersion 2.X.0
        $browserAcceptHeader = $_SERVER["HTTP_ACCEPT"];
        $browserUserAgent = $_SERVER["HTTP_USER_AGENT"];

        // Browser data obtained with JS
        if(!empty($browserData)) {
            $browserJavaEnable = $browserData['javaEnabled'];
            $browserJavaScriptEnabled = $browserData['javascriptEnabled'];
            $browserLanguage = $browserData['browserLanguage'];
            $browserColorDepth = $browserData['browserColorDepth'];
            $browserScreenHeight = $browserData['browserScreenHeight'];
            $browserScreenWidth = $browserData['browserScreenWidth'];
            $browserTZ = $browserData['browserTZ'];
        }

        // Notification URL
        $notificationURL = "$challengeResponseUrl&type=challengeResponse&protocolVersion=$protocolVersion";

        // Method used to add the return parameters to the authentication request for protocolVersion 2.X.0
        $operationRequest->setEMV3DSParamsV2($protocolVersion, $browserAcceptHeader, $browserUserAgent, $browserJavaEnable, $browserJavaScriptEnabled, $browserLanguage, $browserColorDepth, $browserScreenHeight, $browserScreenWidth, $browserTZ, $threeDSServerTransID, $notificationURL, $threeDSCompInd);
    }

    try {
        // Service setting (Signature and Environment)
        $service = new RestOperationService($privateKey, $environment);
        // Send the operation and catch the response
        $response = $service->sendOperation($operationRequest);
        // Response analysis
    } catch (Exception $e) {
        // Error treatment
        echo($e->getMessage());
    }

    // Return response
    return $response;
}

/**
 * Method to launch a challenge
 */
function challenge($protocolVersion, $acsURL, $md, $pareq, $challengeResponseUrl, $creq)
{

    $challengeResponseUrl .= "&type=challengeResponse&protocolVersion=$protocolVersion";
    $challenge = "<iframe id='challengeIframe' name='challengeIframe' src='' target='_parent' referrerpolicy='origin'
					sandbox='allow-same-origin allow-scripts allow-top-navigation allow-forms'
					height='95%' width='100%' style='border: none; display: inline;'></iframe>"
                . "<form id='challengeForm' action='$acsURL' class='round-border' style='display: none' method='POST' enctype='application/x-www-form-urlencoded' target='challengeIframe'>";

    // Adding parameters we need to save send depending on the protocolVersion
    if ($protocolVersion == RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
        $challenge .= "<input type='text' name='PaReq' value='$pareq'><br>"
            . "<input type='text' name='MD' value='$md'><br>"
            . "<input type='text' name='TermUrl' value='$challengeResponseUrl'><br>";
    } else {
        $challenge .= "<input type='text' name='CReq' value='$creq'><br>";
    }

    // Submit

    $challenge .= "</form>"
        . "<script>"
        . "document.getElementById('challengeForm').submit()"
        . "</script>";

    // Return form
    return $challenge;
}

/**
 * Method to receive the challenge response
 */
function challengeResponse($request)
{
    // Operation mandatory data
    $challengeRequest = new RestAuthenticationRequestMessage();

    // Transaction type is authorization
    $transactionType = RESTConstants::$AUTHORIZATION;

    // Environment
    $environment = (!empty($request['env']) ? $request['env'] : RESTConstants::$ENV_SANDBOX);

    // privateKey has to be obtained from your system and not directly written into code, this is just an example coded to be functional out of the box
    $privateKey = ($environment == RESTConstants::$ENV_SANDBOX ? Config::$PRIVATEKEY_SANDBOX : Config::$PRIVATEKEY_PROD);

    // ProtocolVersion
    $protocolVersion = (!empty($request['protocolVersion']) ? $request['protocolVersion'] : "1.0.2");

    // ID Oper
    $idOper = (!empty($request['idOper']) ? $request['idOper'] : "");

    // Operation mandatory data
    $challengeRequest->setAmount((!empty($request['amount']) ? $request['amount'] : "")); // i.e. 1,23 (decimal point depends on currency code)
    $challengeRequest->setCurrency((!empty($request['currency']) ? $request['currency'] : "")); // ISO-4217 numeric currency code
    $challengeRequest->setMerchant((!empty($request['merchantId']) ? $request['merchantId'] : ""));
    $challengeRequest->setTerminal((!empty($request['terminal']) ? $request['terminal'] : ""));
    $challengeRequest->setOrder((!empty($request['merchantOrderId']) ? $request['merchantOrderId'] : ""));
    $challengeRequest->setTransactionType($transactionType);

    // inSite ID Oper information
    $challengeRequest->setOperID($idOper);

    // Receiving parameters we need to save send depending on the protocolVersion
    if ($protocolVersion == RESTConstants::$REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102) {
        // Gathering params
        $pares = $request["PaRes"];
        $md = $request["MD"];

        // Setting params into request
        $challengeRequest->challengeRequestV1($pares, $md);
    } else {
        // Gathering params
        $cres = $request["cres"];

        // Setting params into request
        $challengeRequest->challengeRequestV2($protocolVersion, $cres);
    }

    // Response object for the cardData Request
    $response = null;
    try {
        // Service setting (Signature and Environment)
        $service = new RestAuthenticationRequestService($privateKey, $environment);
        // Send the operation and catch the response
        $response = $service->sendOperation($challengeRequest);
        // Response analysis
    } catch (Exception $e) {
        // Error treatment
        echo($e->getMessage());
    }

    // Return response
    return $response;
}

if(empty($_REQUEST)) {
    // Getting host URL
    $baseUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
    $currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];
    preg_match("/^(.*)\/.*php$/", $currentUrl, $matches);
    $frontUrl = (count($matches) < 2 ? $baseUrl : $matches[1]) . "/paymentFrontend.php";
    header("Location: $frontUrl", true, 301);
    exit();
}

// Type of flow to follow
$type = (empty($_REQUEST['type']) ? "init" : $_REQUEST['type']);

// Check request type
if($type == "init") {
    $response = main($_REQUEST);
} else if ($type == "challengeResponse"){
    $response = challengeResponse($_REQUEST);
}

// Display response
if(!empty($response)) {
    if($response->getResult() == RESTConstants::$RESP_LITERAL_OK) {
        echo("<h1 style='color: green'>Payment has been successfully submitted!</h1>");
        echo("<a href='paymentFrontend.php' target='_parent'>Go Back!</a>");
    } else if($response->getResult() == RESTConstants::$RESP_LITERAL_AUT) {
        echo("<h1 style='color: orange'>Authentication needed!</h1>");
        echo("<a href='paymentFrontend.php' target='_parent'>Go Back!</a>");
    } else if($response->getResult() == RESTConstants::$RESP_LITERAL_KO) {
        echo("<h1 style='color: red'>An error has occurred!</h1>");
    }
}