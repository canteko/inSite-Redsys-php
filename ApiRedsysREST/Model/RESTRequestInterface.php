<?php
	if(!interface_exists('RESTRequestInterface')){
		interface RESTRequestInterface{

			public function getTransactionType();
		}
	}