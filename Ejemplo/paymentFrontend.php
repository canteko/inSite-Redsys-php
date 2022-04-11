<?php
include_once '../ApiRedsysREST/initRedsysApi.php';
include_once 'Config.php';

// Environment
$env = RESTConstants::$ENV_SANDBOX;
// $env = RESTConstants::$ENV_PRODUCTION;

// merchantId and terminal have to be obtained from your system and not directly written into code, this is just an example coded to be functional out of the box
$merchantId = Config::$FUC;
$terminal = Config::$TERMINAL;

// orderId has to be obtained according to the merchant's criteria, this is just an example
$orderId = time();
?>

<html>

<head>
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <script src="lib/jquery/js/jquery-3.4.1.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <?php if ($env == RESTConstants::$ENV_SANDBOX) { ?>
        <script src="https://sis-t.redsys.es:25443/sis/NC/sandbox/redsysV2.js"></script>
    <?php } else if ($env == RESTConstants::$ENV_PRODUCTION) { ?>
        <script src="https://sis.redsys.es/sis/NC/redsysV2.js"></script>
    <?php } ?>

    <!-- This form is just an example -->
    <style>
        #payment-form {
            background-color: lightgrey;
            padding: 15px;
            width: 100%;
            margin: 0;
            border-bottom-right-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        #payment-form label {
            text-align: center;
        }

        #payment-form select,
        #payment-form input {
            width: 100%;
            padding: 5px;
            border: solid grey 1px;
            border-radius: 4px;
        }

        .form-wrapper {
            width: 550px;
            box-shadow: 0px 14px 30px #c7c7c7;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        #card-form {
            width: 100%;
            margin: 5px;
            padding: 10px;
            border-radius: 4px;
            background-color: white;
            height: 340px;
        }

        .flex-center,
        .flex-center-column {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .flex-center-column {
            flex-direction: column;
        }

        .disabled {
            background-color: #f0f0f0;
        }

        .example-card {
            margin: 20px;
            background-color: orange;
            color: white;
            font-weight: bold;
            border-radius: 32px;
            height: 195px;
            width: 320px;
            border: 3px solid black;
            box-shadow: 0px 10px 30px #a5a5a5;
        }

        .example-card input {
            text-align: center;
            margin: 18px;
            border: 1px solid black;
            background-color: lightgray;
            color: black;
            border-radius: 8px;
        }

        .example-cards {
            flex-wrap: wrap;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .header {
            background-color: #b5b5b5;
            padding: 4px;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
        }
    </style>
</head>

<body class="flex-center-column" style="margin: 50px 0">
    <!-- This form is just an example -->
    <div class="form-wrapper">
        <div class="header flex-center">
            <img src="assets/images/redsys.png" alt="" style="max-height: 70px">
        </div>
        <form id="payment-form" action="paymentBackend.php" class="flex-center-column" name="datos" method='POST' enctype='application/x-www-form-urlencoded'>
            <input type="hidden" id="env" name="env" value="<?php echo ($env) ?>"></input>
            <input type="hidden" id="idOper" name="idOper"></input>
            <input type="hidden" id="errorCode" name="errorCode"></input>
            <input type="hidden" id="merchantId" name="merchantId" value="<?php echo ($merchantId) ?>"></input>
            <input type="hidden" id="terminal" name="terminal" value="<?php echo ($terminal) ?>"></input>
            <input type="hidden" id="type" name="type" value="init"></input>

            <!-- Browser details -->
            <input type="hidden" id="javaEnabled" name="javaEnabled"></input>
            <input type="hidden" id="javascriptEnabled" name="javascriptEnabled"></input>
            <input type="hidden" id="browserLanguage" name="browserLanguage"></input>
            <input type="hidden" id="browserColorDepth" name="browserColorDepth"></input>
            <input type="hidden" id="browserScreenHeight" name="browserScreenHeight"></input>
            <input type="hidden" id="browserScreenWidth" name="browserScreenWidth"></input>
            <input type="hidden" id="browserTZ" name="browserTZ"></input>

            <div class="row" style="width: 100%">
                <div class="col-12">
                    <label for="merchantOrderId">Order ID</label>
                    <input type="text" id="merchantOrderId" name="merchantOrderId" class="disabled" readonly></input>
                </div>
                <div class="col-6">
                    <label for="amount">Amount</label>
                    <input type="text" id="amount" name="amount" value="5,95"></input>
                </div>
                <div class="col-6">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency">
                        <option value="978">EUR</option>
                    </select>
                </div>
            </div>

            <a href="javascript:alert(document.datos.idOper.value + '--' + document.datos.errorCode.value)">watch errors</a>

            <div id="card-form"></div>
        </form>
    </div>

    <div class="example-cards">
        <div class="example-card-wrapper flex-center-column" data-pan="4548810000000003" data-date="4912" data-cvv2="123" data-description="Card which uses 1.0.2 authentication"></div>
        <div class="example-card-wrapper flex-center-column" data-pan="4548814479727229" data-date="3412" data-cvv2="123" data-description="EMV3DS 2.1 frictionless authentication without threeDSMethodURL"></div>
        <div class="example-card-wrapper flex-center-column" data-pan="4918019199883839" data-date="3412" data-cvv2="123" data-description="EMV3DS 2.1 challenge authentication with threeDSMethodURL"></div>
        <div class="example-card-wrapper flex-center-column" data-pan="4918010000000044" data-date="3412" data-cvv2="123" data-description="Card that emulates an authentication using EMV3DS 2.1, but produces an error in the middle of the transaction due to merchant not being  enrolled correctly and performs a fallback to complete the flow using V1"></div>
    </div>


    <script>
        var submitted = false;
        var token = "-1";

        function validate() {
            // Validations!! To do by merchant
            alert("Validations by merchant!!");
            return true;
        }

        function loadExampleCards() {
            $('.example-card-wrapper').each(function() {
                let pan = String($(this).data('pan'));
                let date = String($(this).data('date'));
                let cvv2 = String($(this).data('cvv2'));
                let description = String($(this).data('description'));

                $(this).addClass("flex-center-column");

                let content = "<div class='example-card flex-center-column'>" +
                    "<div style='width: 100%; height: 25px; background-color: black; text-align: center;'>Example Card</div>" +
                    "<div class='flex-center' style='width: 100%;'>" +
                    "<input class='pan' value='" + pan + "'></input>" +
                    "</div>" +
                    "<div class = 'flex-center' style = 'width: 100%;'>" +
                    "<input style = 'width: 50%;' value='" + date.slice(2, 4) + date.slice(0, 2) + "'></input>" +
                    "<input style = 'width: 50%;' value='" + cvv2 + "'></input>" +
                    "</div>" +
                    "</div>" +
                    "<div style='max-width: 300px'>" + description + "</div>";

                $(this).append(content);
            });
        }

        window.addEventListener("message", function receiveMessage(event) {
            storeIdOper(event, "idOper", "errorCode", validate);
            token = $('#idOper').val();
            if (submitted == false && token != '' && token != '-1') {
                $('#payment-form').submit();
                submitted = true;
            }
        });

        var merchantOrderId = "<?php echo ($orderId) ?>";
        var merchantId = "<?php echo ($merchantId) ?>";
        var merchantTerminal = "<?php echo ($terminal) ?>";

        $('#merchantOrderId').val(merchantOrderId);

        getInSiteForm('card-form', 'background-color: orange; color: black;', '', '', '', 'Pay', merchantId, merchantTerminal, merchantOrderId, 'EN');

        // Browser details
        $('#javaEnabled').val(navigator.javaEnabled());
        $('#javascriptEnabled').val(true);
        $('#browserLanguage').val(navigator.language);
        $('#browserColorDepth').val(window.screen.colorDepth);
        $('#browserScreenHeight').val(window.screen.height);
        $('#browserScreenWidth').val(window.screen.width);
        $('#browserTZ').val((new Date()).getTimezoneOffset());

        loadExampleCards();

        $('.example-card input').click(function() {
            /* Get the text field */
            var copyText = $(this)[0];

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            document.execCommand("copy");
        });
    </script>
</body>

</html>