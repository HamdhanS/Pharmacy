<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id2 = $_SESSION['currentuser'];


if (isset($_POST['r1'])) {
    $pid = $_POST['pid'];
    $mname = $_POST['mname'];
    $pur = $_POST['pur'];

    try {
        $stmt = $conn->prepare("INSERT INTO pres (pid, mname, pur, dr) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $pid, $mname, $pur, $id2);
        $stmt->execute();
 echo "<script>
    alert('Registered Successfully!');
    window.location.href='AddPres.php?id=" . urlencode($id2) . "';
</script>";
			
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/AddProduct.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
	 <style>
        video { width: 80%;  }
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
                <a href="DoctorHomepage.php?id=<?= urlencode($id2) ?>">HOME</a>
                <a href="AddPres.php?id=<?= urlencode($id2) ?>">ADD PRESCRIPTION</a>
            </div>
        </div>
    </div>
</div>

<div class="active">
    <div class="filler"></div>
    <h2 style="padding-left:100px;">Add Product</h2>
    
    <?php
    if (isset($_SESSION['successMsg'])) {
        echo "<p style='color:green;'>" . $_SESSION['successMsg'] . "</p>";
        unset($_SESSION['successMsg']); // Clear message after displaying
    }
    ?>

    <form action="AddPres.php?id=<?= htmlspecialchars($id2) ?>" method="post">
        <div class="bigcard">
            <div class="bigcard1">
                <h3 style="width:500px;">Scan/Enter Patient ID</h3>
                <input type="text" name="pid" id="scan" required>
                
                <h3>Enter Medicine Name</h3>
                <input type="text" name="mname" required>
                
                <h3>Purpose</h3>
                <input type="text" name="pur" required>
                
                <br><br><br>
                <input type="submit" value="Add" name="r1" style="height:40px; width:77%;">
            </div>
            <div class="bigcard2">
             <h2>Scan</h2>
    <video id="preview"></video>
   
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
            document.getElementById('scan').value = content;
            
        });
    </script>
            </div>
        </div>
    </form>
</div>

</body>
</html>
