<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <!-- Include html5-qrcode library -->
    <script src="../node_modules/html5-qrcode/html5-qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        #reader {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border: 2px solid #333;
            padding: 10px;
        }
        #result {
            margin: 20px auto;
            padding: 10px;
            width: 90%;
            max-width: 500px;
            border: 1px solid #333;
            background-color: #fff;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <p>Scan a QR code using your camera and see the result below.</p>

    <!-- Live feed QR code scanner -->
    <div id="reader"></div>

    <!-- Display scanned QR code result -->
    <div id="result">Waiting for QR code...</div>

    <!-- Submit scanned result to PHP -->
    <form id="resultForm" method="POST">
        <input type="hidden" name="qr_data" id="qrDataInput">
        <button type="submit">Submit Scanned Data</button>
    </form>

    <script>
        // Initialize the QR code scanner
        function onScanSuccess(qrCodeMessage) {
            console.log("Scanned content:", qrCodeMessage);

            // Display scanned QR code result
            document.getElementById('result').textContent = "Scanned QR Code: " + qrCodeMessage;

            // Populate the hidden form input
            document.getElementById('qrDataInput').value = qrCodeMessage;
        }

        function onScanError(errorMessage) {
            // Optionally log errors
            console.error("Scan error:", errorMessage);
        }

        // Start the QR code scanner
        const html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, // Use back camera for scanning
            {
                fps: 10, // Frames per second
                qrbox: { width: 250, height: 250 } // Scanning area
            },
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error("Camera start failed:", err);
            alert("Unable to start camera. Please check permissions or try a different browser.");
        });
    </script>

    <?php
    // Process scanned data in PHP
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $scannedData = $_POST['qr_data'] ?? '';
        echo "<div id='result'>Server received: " . htmlspecialchars($scannedData, ENT_QUOTES, 'UTF-8') . "</div>";
    }
    ?>
</body>
</html>
