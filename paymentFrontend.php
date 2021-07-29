<html>

<head>
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <script src="lib/jquery/js/jquery-3.4.1.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://sis-t.redsys.es:25443/sis/NC/sandbox/redsysV2.js"></script>

    <!-- This form is just an example -->
    <style>
        #payment-form {
            background-color: lightgrey;
            padding: 15px;
            width: 550px;
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

        #card-form {
            width: 100%;
            margin: 5px;
            padding: 10px;
            border-radius: 4px;
            background-color: white;
            height: 340px;
        }

        .flex-center-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .disabled {
            background-color: #f0f0f0;
        }
    </style>
</head>

<?php
    // merchantId and terminal have to be obtained from your system and not directly written into code, this is just an example coded to be functional out of the box
    $merchantId = "999008881";
    $terminal = "1";

    // orderId has to be obtained according to the merchant's criteria, this is just an example
    $orderId = time();
?>

<body class="flex-center-column" style="height: 100%;">
    <!-- This form is just an example -->
    <form id="payment-form" action="paymentBackend.php?prueba=123" class="flex-center-column" name="datos" method='POST' enctype='application/x-www-form-urlencoded'>
        <input type="hidden" id="idOper" name="idOper"></input>
        <input type="hidden" id="errorCode" name="errorCode"></input>
        <input type="hidden" id="merchantId" name="merchantId" value="<?php echo ($merchantId) ?>"></input>
        <input type="hidden" id="terminal" name="terminal" value="<?php echo ($terminal) ?>"></input>
        <input type="hidden" id="type" name="type" value="init"></input>
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

    <script>
        var submitted = false;
        var token = "-1";

        function validate() {
            // Validations!! To do by merchant
            alert("Validations by merchant!!");
            return true;
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

        getInSiteForm('card-form', '', '', '', '', 'Realizar pago', merchantId, merchantTerminal, merchantOrderId, 'ES');
    </script>
</body>

</html>