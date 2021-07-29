<html>

<head>
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/jquery/css/jquery-ui.css">
    <script src="lib/jquery/js/jquery-3.4.1.js"></script>
    <script src="lib/jquery/js/jquery-ui.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://sis-t.redsys.es:25443/sis/NC/sandbox/redsysV2.js"></script>

    <style>
        #pago-form {
            background-color: lightgrey;
            padding: 15px;
            width: 550px;
        }

        #pago-form label {
            text-align: center;
        }

        #pago-form select,
        #pago-form input {
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
$merchantId = "999008881";
$terminal = "1";
$orderId = time();
?>

<body class="flex-center-column" style="height: 100%;">
    <form id="pago-form" action="paymentBackend.php" class="flex-center-column" name="datos" method='POST' enctype='application/x-www-form-urlencoded'>
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
            <!-- La clave privada, el ID del comercio y la terminal no se debe obtener desde un formulario, estos campo sirve únicamente para poder hacer pruebas con este ejemplo -->
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
            //Insertar validaciones…
            alert("Esto son validaciones propias");
            return true;
        }

        window.addEventListener("message", function receiveMessage(event) {
            storeIdOper(event, "idOper", "errorCode", validate);
            token = $('#idOper').val();
            if (submitted == false && token != '' && token != '-1') {
                $('#pago-form').submit();
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