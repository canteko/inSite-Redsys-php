<?php

include_once '../ApiRedsysREST/Constants/RESTConstants.php';

/**
 * Config
 */
if (!class_exists('Config')) {
    class Config
    {
        // Merchant identification
        public static $FUC = "999008881";
        public static $TERMINAL = "20";

        // This has to be obtained from your system and not directly written into code, this is just an example coded to be functional out of the box
        public static $PRIVATEKEY_SANDBOX = "sq7HjrUOBfKmC576ILgskD5srU870gJ7";
        public static $PRIVATEKEY_PROD = "<PRIVATEKEY_PROD>";
    }
}
