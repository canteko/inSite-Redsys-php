<?php
	if(!interface_exists('RESTResponseInterface')){
		interface RESTResponseInterface{
		
			public function setResult($code);
			public function getResult();
			public function getTransactionType();
			public function PSD2analysis();
			public function protocolVersionAnalysis();
			public function getMDParameter();
			public function getAcsURLParameter();
			public function getPAReqParameter();
			public function getThreeDSServerTransID();
			public function getThreeDSMethodURL();
			function getThreeDSInfo();
			public function getCreqParameter();
			function getExemption();
			function getCOFTxnid();
			function getDCCCurrency();
			function getDCCAmount();
		}
	}