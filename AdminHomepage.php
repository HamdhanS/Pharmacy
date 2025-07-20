<?php
// Include the phpqrcode library (adjust path if necessary)
require_once 'phpqrcode/qrlib.php';

// Set up directories for temporary QR code images
$PNG_TEMP_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
$PNG_WEB_DIR = 'temp/';

// Create temp directory if it doesn't exist
if (!file_exists($PNG_TEMP_DIR)) {
    mkdir($PNG_TEMP_DIR, 0777, true);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data"])) {
    $data = trim($_POST["data"]);
    
    if ($data == '') {
        die("Data cannot be empty!");
    }
    
    // Set error correction level and size
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 12;
    
    // Generate a unique filename for the QR code
    $qrFilename = $PNG_TEMP_DIR . 'qr_' . md5($data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    
    // Generate the QR code and save it to file
    QRcode::png($data, $qrFilename, $errorCorrectionLevel, $matrixPointSize, 2);
    
    // Load the background image (make sure background.jpg exists)
    $backgroundFile = 'background.jpg';
    if (!file_exists($backgroundFile)) {
        die("Background image not found.");
    }
    $background = imagecreatefromjpeg($backgroundFile); // Use imagecreatefrompng() if PNG
    
    // Load the generated QR code image
    $qrImage = imagecreatefrompng($qrFilename);
    
    // Get dimensions of background and QR code
    $bgWidth = imagesx($background);
    $bgHeight = imagesy($background);
    $qrWidth = imagesx($qrImage);
    $qrHeight = imagesy($qrImage);
    
    // Calculate position to center the QR code on the background
    $posX = ($bgWidth - $qrWidth) / 1.4;
    $posY = ($bgHeight - $qrHeight) / 1.7;
    
    // Merge the QR code onto the background image
    imagecopy($background, $qrImage, $posX, $posY, 0, 0, $qrWidth, $qrHeight);
    
    // Save the final image to a new file
    $finalFilename = $PNG_TEMP_DIR . 'final_' . md5($data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    imagepng($background, $finalFilename);
    
    // Free up memory
    imagedestroy($background);
    imagedestroy($qrImage);
    
    // Display the final merged image
    echo "<h3>QR Code Generated!</h3>";
	echo "<a href='AdminHomepage.php'>Back</a><br/><br/>";
    echo "<img src='" . $PNG_WEB_DIR . basename($finalFilename) . "' alt='QR Code with Background' width='800'>";
    
} else {
    // Display the form if not submitted
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Generate QR Code</title>
		<link rel="stylesheet" href="css/Homepage.css">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

.open-button {
  background-color: #1996d7;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
  font-size:18px;
}

.chat-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 200px;
}

.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

.form-container .cancel {
  background-color: red;
}

.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
</style>
    </head>
    <body>
	<div class="main">
    <div class="topbar1"></div>
    <div class="topbar2">
        <div class="container1">
            <div class="logout-btn">
                <a href="Logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="header">
        <div class="container2">
            <div class="navbar">
                <a> Welcome ADMIN</a>
            </div>
        </div>
    </div>
</div>

<div class="active">
    <div class="filler"></div>
    
    <div class="filler2"></div>
    <div class="card">
               <form method="post" action="">
            <label>Enter Text for QR Code:</label>
            <input type="text" name="data" required>
            <button type="submit">Generate QR</button>
        </form>
    </div>
</div>



    </body>
    </html>
    <?php
}
?>
