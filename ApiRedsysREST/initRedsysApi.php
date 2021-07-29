<?php 
	$GLOBALS["REDSYS_API_PATH"]=realpath(dirname(__FILE__));
	$GLOBALS["REDSYS_LOG_ENABLED"]=true;

	include_once $GLOBALS["REDSYS_API_PATH"]."/Model/message/RESTOperationMessage.php";
	include_once $GLOBALS["REDSYS_API_PATH"]."/Model/message/RESTAuthenticationRequestMessage.php";
	include_once $GLOBALS["REDSYS_API_PATH"]."/Service/RESTService.php";
	include_once $GLOBALS["REDSYS_API_PATH"]."/Service/Impl/RESTInitialRequestService.php";
	include_once $GLOBALS["REDSYS_API_PATH"]."/Service/Impl/RESTAuthenticationRequestService.php";
	include_once $GLOBALS["REDSYS_API_PATH"]."/Service/Impl/RESTOperationService.php";
	include_once $GLOBALS["REDSYS_API_PATH"].'/Utils/RESTLogger.php';
	RESTLogger::initialize($GLOBALS["REDSYS_API_PATH"]."/Log/", RESTLogger::$DEBUG);