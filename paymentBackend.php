<?php

include_once 'ApiRedsysREST/initRedsysApi.php';

function main($request, $privateKey)
{
    // Recogemos las variables
    // idOper
    $idOper = (!empty($request['idOper']) ? $request['idOper'] : "");

    // Esta es la ID de la moneda que se va a usar en la transacción, 978 es EUR en el estándar ISO 4217​
    $currency = (!empty($request['currency']) ? $request['currency'] : "");

    // ID del comercio, terminal del comercio y clave privada, esto tiene que ser obtenido de la base de datos
    // de la tienda o bien de algún archivo de configuración.
    // MUY IMPORTANTE guardar la privateKey en un lugar seguro
    $merchant = (!empty($request['merchantId']) ? $request['merchantId'] : "999008881");
    $terminal = (!empty($request['terminal']) ? $request['terminal'] : "1");

    // EL tipo de transacción es autorización
    $transactionType = RESTConstants::$AUTHORIZATION;

    // Cantidad de la compra y datos de la tarjeta, estos datos se obtienen del cliente a través de un formulario
    // y del cálculo del valor de la operación
    // El valor de la compra está en VALOR*100, por lo que, en este ejemplo, el valor de la compra es 1,99€
    $amountRaw = str_replace(',', '.', (!empty($request['amount']) ? $request['amount'] : "1.99"));
    $amount = strval(intval($amountRaw) * 100);

    // ID de la transacción
    $order = (!empty($request['merchantOrderId']) ? $request['merchantOrderId'] : time());

    // Challenge Response URL
    $challengeResponseUrl = "http://localhost:8080/EjemploPHP/paymentBackend.php?"
        . "&merchantOrderId=" . $order
        . "&idOper=" . $idOper
        . "&currency=" . $currency
        . "&merchantId=" . $merchant
        . "&terminal=" . $terminal
        . "&amount=" . $amount;

    // TEST
    $environment = RESTConstants::$ENV_SANDBOX;
    $protocolVersion = $threeDSServerTransID = $threeDSMethodURL = "";

    // Operación inicial
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

    $toResponse = null;
    // If response is AUT, we need authentication,
    // If response is OK, initialization is OK and we do not need authentication, but authentication is available anyway
    // If response is KO, something went wrong
    // In order to pay securely, we are going to use authentication despite of response (If it is not a KO)
    if ($ioResponse->getResult() == RESTConstants::$RESP_LITERAL_AUT || $ioResponse->getResult() == RESTConstants::$RESP_LITERAL_OK) {
        $toResponse = authenticationOperation($privateKey, $order, $amount, $currency, $merchant, $terminal, $transactionType, $idOper, $environment, $protocolVersion, $threeDSServerTransID, $challengeResponseUrl);
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
 * Method to authenticate an operation request. This request depend on the initial request parameter "protocolVersion"
 */
function authenticationOperation($privateKey, $order, $amount, $currency, $merchant, $terminal, $transactionType, $idOper, $environment, $protocolVersion, $threeDSServerTransID = "", $challengeResponseUrl = "")
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
        $notificationURL = "$challengeResponseUrl&type=challengeResponse&protocolVersion=$protocolVersion";
        $threeDSCompInd = "Y";

        //Method that can be use to add the return parameters to the authentication request for protocolVersion 2.X.0
        $operationRequest->setEMV3DSParamsV2($protocolVersion, $browserAcceptHeader, $browserUserAgent, $browserJavaEnable, $browserJavaScriptEnabled, $browserLanguage, $browserColorDepth, $browserScreenHeight, $browserScreenWidth, $browserTZ, $threeDSServerTransID, $notificationURL, $threeDSCompInd);
    }

    try {
        // Service setting (Signature and Environment)
        $service = new RestOperationService($privateKey, $environment);
        //Send the operation and catch the response
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
function challengeResponse($request, $privateKey)
{
    // Operation mandatory data
    $challengeRequest = new RestAuthenticationRequestMessage();

    // EL tipo de transacción es autorización
    $transactionType = RESTConstants::$AUTHORIZATION;

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

    //Card Data information
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
        $service = new RestAuthenticationRequestService($privateKey, RESTConstants::$ENV_SANDBOX);
        //Send the operation and catch the response
        $response = $service->sendOperation($challengeRequest);
        // Response analysis
    } catch (Exception $e) {
        // Error treatment
        echo($e->getMessage());
    }

    // Return response
    return $response;
}

$inputPostData = json_decode(file_get_contents("php://input"), true);
$inputPostData = (empty($inputPostData) ? array() : $inputPostData);
$postData = (!empty($_POST) ? $_POST : $inputPostData);
$request = array_merge($postData, $_GET);
$type = (empty($request['type']) ? "init" : $request['type']);
$privateKey = "sq7HjrUOBfKmC576ILgskD5srU870gJ7";
if($type == "init") {
    $response = main($request, $privateKey);
} else if ($type == "challengeResponse"){
    $response = challengeResponse($request, $privateKey);
}

if(!empty($response)) {
    if($response->getResult() == RESTConstants::$RESP_LITERAL_OK) {
        echo("<h1 style='color: green'>Payment has been successfully submitted!</h1>");
    } else if($response->getResult() == RESTConstants::$RESP_LITERAL_AUT) {
        echo("<h1 style='color: orange'>Authentication needed!</h1>");
    } else if($response->getResult() == RESTConstants::$RESP_LITERAL_KO) {
        echo("<h1 style='color: red'>An error has occurred!</h1>");
    }
}
