<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST["text"] ?? 'Hello, QR Code!';
    $size = $_POST["size"] ?? 300; // Default size
    $ecc = $_POST["ecc"] ?? QRCode::ECC_L; // Error correction level

    // Set QR code options
    $options = new QROptions([
        'eccLevel'    => $ecc,
        'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
        'scale'       => $size / 33, // Adjust scale based on size
    ]);

    // Generate QR Code
    $qrcode = new QRCode($options);
    $qrImage = $qrcode->render($text);

    // Define folder path
    $folder = 'qrcodes/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true); // Create folder if not exists
    }

    // Generate filename
    $filename = $folder . 'qr_' . time() . '.png';

    // Save QR code image to folder
    file_put_contents($filename, base64_decode(explode(',', $qrImage)[1]));

    echo "QR Code saved: <a href='$filename' download>Download QR Code</a><br>";
    echo "<img src='$filename' alt='QR Code'>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>

    <h2>Generate QR Code</h2>
    <form method="POST">
        <label>Text to Encode:</label>
        <input type="text" name="text" required>
        <br><br>
        
        <label>Size (pixels):</label>
        <input type="number" name="size" value="300">
        <br><br>
        
        <label>Error Correction Level:</label>
        <select name="ecc">
            <option value="L">L - 7% damage</option>
            <option value="M">M - 15% damage</option>
            <option value="Q">Q - 25% damage</option>
            <option value="H">H - 30% damage</option>
        </select>
        <br><br>

        <button type="submit">Generate & Download QR Code</button>
    </form>

</body>
</html>
