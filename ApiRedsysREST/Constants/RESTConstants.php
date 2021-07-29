<?php 
	if(!class_exists('RESTConstants')){
		class RESTConstants {
			
			// Environments
			public static $INICIA = "0";
			public static $TRATA = "1";

			public static $ENV_SANDBOX = "0";
			// Endpoints TEST
			public static $SANDBOX_JS = "https://sis-t.redsys.es:25443/sis/NC/sandbox/redsys2.js";
			public static $SANDBOX_ENDPOINT = "https://sis-t.redsys.es:25443/sis/rest/trataPeticionREST";
			public static $SANDBOX_ENDPOINT_INICIA = "https://sis-t.redsys.es:25443/sis/rest/iniciaPeticionREST";

			public static $ENV_PRODUCTION = "1";
			public static $PRODUCTION_JS = "https://sis.redsys.es/sis/NC/redsys.js";
			public static $PRODUCTION_ENDPOINT = "https://sis.redsys.es/sis/rest/trataPeticionREST";
			public static $PRODUCTION_ENDPOINT_INICIA = "https://sis.redsys.es/sis/rest/iniciaPeticionREST";

			public static $CONNECTION_TIMEOUT_VALUE = 10;
			public static $READ_TIMEOUT_VALUE = 120;
			public static $SSL_TLSv12 = 6;
			public static $TARGET = "http://webservice.sis.sermepa.es";
			public static $SERVICE_NAME = "SerClsWSEntradaService";
			public static $PORT_NAME = "SerClsWSEntrada";
			
			
			// Request message constants
			public static $REQUEST_SIGNATURE = "DS_SIGNATURE";
			public static $REQUEST_SIGNATUREVERSION_VALUE = "HMAC_SHA256_V1";
			public static $REQUEST_MERCHANT_ORDER = "DS_MERCHANT_ORDER";
			public static $REQUEST_MERCHANT_MERCHANTCODE = "DS_MERCHANT_MERCHANTCODE";
			public static $REQUEST_MERCHANT_TERMINAL = "DS_MERCHANT_TERMINAL";
			public static $REQUEST_MERCHANT_TRANSACTIONTYPE = "DS_MERCHANT_TRANSACTIONTYPE";
			public static $REQUEST_MERCHANT_IDOPER = "DS_MERCHANT_IDOPER";
			public static $REQUEST_MERCHANT_CURRENCY = "DS_MERCHANT_CURRENCY";
			public static $REQUEST_MERCHANT_AMOUNT = "DS_MERCHANT_AMOUNT";
			public static $REQUEST_MERCHANT_IDENTIFIER = "DS_MERCHANT_IDENTIFIER";
			public static $REQUEST_MERCHANT_IDENTIFIER_REQUIRED = "REQUIRED";
			public static $REQUEST_MERCHANT_DIRECTPAYMENT = "DS_MERCHANT_DIRECTPAYMENT";
			public static $REQUEST_MERCHANT_DIRECTPAYMENT_TRUE = "true";
			
			//Nuevos para API REST-IS respecto a API IS
			public static $REQUEST_MERCHANT_DIRECTPAYMENT_MOTO = "MOTO";
			public static $REQUEST_MERCHANT_CVV2 = "DS_MERCHANT_CVV2";
			public static $REQUEST_MERCHANT_PAN = "DS_MERCHANT_PAN";
			public static $REQUEST_MERCHANT_EXPIRYDATE = "DS_MERCHANT_EXPIRYDATE";
			public static $REQUEST_MERCHANT_COF_INI = "DS_MERCHANT_COF_INI";
			public static $REQUEST_MERCHANT_COF_INI_TRUE = "S";
			public static $REQUEST_MERCHANT_COF_TYPE = "DS_MERCHANT_COF_TYPE";
			public static $REQUEST_MERCHANT_COF_TYPE_INSTALLMENTS = "I";
			public static $REQUEST_MERCHANT_COF_TYPE_RECURRING = "R";
			public static $REQUEST_MERCHANT_COF_TYPE_REAUTHORIZATION = "H";
			public static $REQUEST_MERCHANT_COF_TYPE_RESUBMISSION = "E";
			public static $REQUEST_MERCHANT_COF_TYPE_DELAYED = "D";
			public static $REQUEST_MERCHANT_COF_TYPE_INCREMENTAL = "M";
			public static $REQUEST_MERCHANT_COF_TYPE_NOSHOW = "N";
			public static $REQUEST_MERCHANT_COF_TYPE_OTRAS  = "C";
	
			public static $REQUEST_MERCHANT_COF_TXNID = "DS_MERCHANT_COF_TXNID";
			
			//No se usan
			//public static $REQUEST_REQUEST_TAG = "REQUEST";
			//public static $REQUEST_DATOSENTRADA_TAG = "DATOSENTRADA";
			//public static $REQUEST_SIGNATUREVERSION_TAG = "DS_SIGNATUREVERSION";
			//public static $REQUEST_MERCHANT_SIS_CURRENCY_TAG = "Sis_Divisa";
			//public static $REQUEST_MERCHANT_SESSION_TAG = "DS_MERCHANT_SESION";
			//public static $REQUEST_MERCHANT_DIRECTPAYMENT_3DS = "3DS";
			 

			//Request EMV3DS constants
			public static $REQUEST_MERCHANT_EMV3DS = "DS_MERCHANT_EMV3DS";
			public static $REQUEST_MERCHANT_EMV3DS_THREEDSINFO = "threeDSInfo"; 	
			public static $REQUEST_MERCHANT_EMV3DS_CARDDATA = "CardData";
			public static $REQUEST_MERCHANT_EMV3DS_AUTHENTICACIONDATA = "AuthenticationData";
			public static $REQUEST_MERCHANT_EMV3DS_CHALLENGEREQUEST = "ChallengeRequest";
			public static $REQUEST_MERCHANT_EMV3DS_CHALLENGEREQUESTRESPONSE = "ChallengeResponse";
			public static $REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION = "protocolVersion";
			public static $REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_102 = "1.0.2";
			public static $REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_210 = "2.1.0";
			public static $REQUEST_MERCHANT_EMV3DS_PROTOCOLVERSION_220 = "2.2.0";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_ACCEPT_HEADER = "browserAcceptHeader";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_ACCEPT_HEADER_VALUE = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8,application/json";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_USER_AGENT = "browserUserAgent";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_USER_AGENT_VALUE = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_JAVA_ENABLE = "browserJavaEnabled";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_JAVASCRIPT_ENABLE = "browserJavascriptEnabled";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_LANGUAGE = "browserLanguage";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_COLORDEPTH = "browserColorDepth";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_SCREEN_HEIGHT = "browserScreenHeight";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_SCREEN_WIDTH = "browserScreenWidth";
			public static $REQUEST_MERCHANT_EMV3DS_BROWSER_TZ = "browserTZ";
			public static $REQUEST_MERCHANT_EMV3DS_THREEDSSERVERTRANSID = "threeDSServerTransID";
			public static $REQUEST_MERCHANT_EMV3DS_NOTIFICATIONURL= "notificationURL";
			public static $REQUEST_MERCHANT_EMV3DS_THREEDSCOMPIND = "threeDSCompInd";
			public static $REQUEST_MERCHANT_EMV3DS_PARES = "PARes";
			public static $REQUEST_MERCHANT_EMV3DS_MD = "MD";
			public static $REQUEST_MERCHANT_EMV3DS_CRES = "cres";
			public static $REQUEST_MERCHANT_EXEMPTION = "DS_MERCHANT_EXCEP_SCA";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_YES = "Y";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_LWV = "LWV";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_TRA = "TRA";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_MIT = "MIT";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_COR = "COR";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_ATD = "ATD";
			public static $REQUEST_MERCHANT_EXEMPTION_VALUE_NDF = "NDF";


			// Response message constants
			public static $RESPONSE_AMOUNT = "Ds_Amount";
			public static $RESPONSE_CURRENCY = "Ds_Currency";
			public static $RESPONSE_ORDER = "Ds_Order";
			public static $RESPONSE_SIGNATURE = "Ds_Signature";
			public static $RESPONSE_MERCHANT = "Ds_MerchantCode";
			public static $RESPONSE_TERMINAL = "Ds_Terminal";
			public static $RESPONSE_DS_RESPONSE = "Ds_Response";
			public static $RESPONSE_AUTHORIZATION_CODE = "Ds_AuthorisationCode";
			public static $RESPONSE_TRANSACTION_TYPE = "Ds_TransactionType";
			public static $RESPONSE_SECURE_PAYMENT = "Ds_SecurePayment";
			public static $RESPONSE_LANGUAGE = "Ds_Language";
			public static $RESPONSE_MERCHANT_DATA = "Ds_MerchantData";
			public static $RESPONSE_CARD_COUNTRY = "Ds_Card_Country";
			public static $RESPONSE_CARD_NUMBER = "Ds_CardNumber";
			public static $RESPONSE_EXPIRY_DATE = "Ds_ExpiryDate";
			public static $RESPONSE_MERCHANT_IDENTIFIER = "Ds_CardNumber";
			public static $RESPONSE_AUTHENTICATION_REQUIRED = "authenticationRequired";
			public static $RESPONSE_AUTHENTICATION_NOT_REQUIRED = "authenticationNotRequired";

			//No viene en Java y no se usa
			//public static $RESPONSE_CODE = "CODIGO";


			// Response message DCC
			public static $REQUEST_DS_MERCHANT_DCC = "DS_MERCHANT_DCC";
			public static $REQUEST_DS_MERCHANT_DCC_TRUE = "Y";
			public static $REQUEST_DS_MERCHANT_DCC_MONEDA = "monedaDCC";
			public static $REQUEST_DS_MERCHANT_DCC_IMPORTE = "importeDCC";

			public static $RESPONSE_DCC_CURRENCY_TAG = "moneda";
			public static $RESPONSE_DCC_CURRENCY_STRING_TAG = "litMoneda";
			public static $RESPONSE_DCC_CURRENCY_CODE_TAG = "litMonedaR";
			public static $RESPONSE_DCC_CHANGE_RATE_TAG = "cambio";
			public static $RESPONSE_DCC_CHANGE_DATE_TAG = "fechaCambio";
			public static $RESPONSE_DCC_CHECKED_TAG = "checked";
			public static $RESPONSE_DCC_AMOUNT_TAG = "importe";
			public static $RESPONSE_DCC_MARGIN_TAG = "margenDCC";
			public static $RESPONSE_DCC_BANK_NAME_TAG = "nombreEntidad";
			
			//No vienen en JAVA:
			public static $RESPONSE_DCC_TAG = "DCC";
			public static $RESPONSE_ACS_URL_TAG = "Ds_AcsUrl";
			public static $RESPONSE_JSON_ACS_ENTRY="acsURL";
			public static $RESPONSE_JSON_PAREQ_ENTRY="PAReq";
			public static $RESPONSE_JSON_PARES_ENTRY="PARes"; //No se usa
			public static $RESPONSE_JSON_MD_ENTRY="MD";
			public static $RESPONSE_JSON_PROTOCOL_VERSION_ENTRY="protocolVersion";
			public static $RESPONSE_JSON_THREEDSINFO_ENTRY="threeDSInfo";
			public static $RESPONSE_3DS_CHALLENGE_REQUEST="ChallengeRequest";
			public static $RESPONSE_3DS_CHALLENGE_RESPONSE="ChallengeResponse"; //No se usa
			public static $RESPONSE_3DS_VERSION_1="1.0.2";
			public static $RESPONSE_3DS_VERSION_2_PREFIX="2.";
			

			//Response 3DSecure
			public static $RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION = "protocolVersion";
			public static $RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION_102 = "NO_3DS_v2";
			public static $RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION_210 = "2.1.0";
			public static $RESPONSE_MERCHANT_EMV3DS_PROTOCOLVERSION_220 = "2.2.0";
			public static $RESPONSE_PSD2_TRUE = "Y";
			public static $RESPONSE_PSD2_FALSE = "N";
			public static $RESPONSE_MERCHANT_EMV3DS_THREEDSMETHODURL = "threeDSMethodURL";
			public static $RESPONSE_MERCHANT_EMV3DS_THREEDSINFO = "threeDSInfo";
			public static $RESPONSE_MERCHANT_EMV3DS_THREEDSSERVERTRANSID = "threeDSServerTransID";
			public static $RESPONSE_MERCHANT_EMV3DS_ACSURL = "acsURL";
			public static $RESPONSE_MERCHANT_EMV3DS_CREQ = "creq";
			public static $RESPONSE_MERCHANT_EMV3DS_MD = "MD";
			public static $RESPONSE_MERCHANT_EMV3DS_PAREQ = "PAReq";			
			//No viene en Java:
			public static $RESPONSE_MERCHANT_EMV3DS_CRES = "cres";


			// Response codes
			public static $RESP_CODE_OK = "0";
			public static $RESP_LITERAL_OK = "OK";
			public static $RESP_LITERAL_KO = "KO";
			public static $RESP_LITERAL_AUT = "AUT";
			//No vienen en Java
			public static $AUTHORIZATION_OK = 0000;
			public static $CONFIRMATION_OK = 900;
			public static $CANCELLATION_OK = 400;
	
			public static $AUTHORIZATION = "0";
			public static $REFUND = "3";
			public static $PREAUTHORIZATION = "1";
			public static $CONFIRMATION = "2";
			public static $CANCELLATION = "9";
			public static $VALIDATION = "7";
			public static $VALIDATION_CONFIRMATION = "8";
			public static $DELETE_REFERENCE = "44";
			public static $NULL = "NULL";


			//Get the JavaScript path depending on the environment we are in, production or test (sandbox)
			public static function getJSPath($env){
				if($env==RESTConstants::$ENV_PRODUCTION){
					return RESTConstants::$PRODUCTION_JS;
				}
				else{
					return RESTConstants::$SANDBOX_JS;
				}
			}

			//Get the endpoint path depending on the environment and the operation we are in, production/test(sandbox) and inicia/trata
			public static function getEnviromentEndpoint($env, $operation){
				
				if($env == RESTConstants::$ENV_PRODUCTION){
					if($operation == RESTConstants::$INICIA){
						return RESTConstants::$PRODUCTION_ENDPOINT_INICIA;
					}
					else{return RESTConstants::$PRODUCTION_ENDPOINT;}
				}
				else{
					if($operation == RESTConstants::$INICIA){
						return RESTConstants::$SANDBOX_ENDPOINT_INICIA;
					}
					else{return RESTConstants::$SANDBOX_ENDPOINT;}
				}
			}

		}


	}