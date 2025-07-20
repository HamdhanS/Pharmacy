<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <style>
        body { text-align: center; padding: 20px; }
        video { width: 50%; margin: 10px auto; display: block; }
        input { width: 50%; padding: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Scan & Make Payment</h2>
    <video id="preview"></video>
    <input type="text" id="text" readonly placeholder="Scanned QR Code">

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found');
            }
        }).catch(function (e) {
            console.error(e);
        });

        scanner.addListener('scan', function (content) {
            document.getElementById('text').value = content;
            
        });
    </script>
</body>
</html>
