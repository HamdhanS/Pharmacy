<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

// Check if doctor is logged in
if (!isset($_SESSION['currentuser'])) {
    header("Location: DoctorLogin.html");
    exit();
}

// Get Doctor ID from Session
$guid = $_SESSION['currentuser'];

// Establish Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to Get Doctor Details
$query = "SELECT dname, sid, address, phno FROM doctor WHERE sid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $guid);
$stmt->execute();
$result = $stmt->get_result();

// Fetch Data
$doctor = $result->fetch_assoc();

// Close Connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Homepage</title>
    <link rel="stylesheet" href="css/Homepage.css">
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
                <a href="DoctorHomepage.php?id=<?= urlencode($guid) ?>">HOME</a>
                <a href="AddPres.php?id=<?= urlencode($guid) ?>">ADD PRESCRIPTION</a>
            </div>
        </div>
    </div>

    <div class="active">
        <div class="filler"></div>
        <h2>Welcome, <?= htmlspecialchars($doctor['dname']) ?></h2>

        <div class="filler2"></div>
        <div class="card">
            <img src="images/User.png" class="Avatar" width="234" height="234">
            <div class="container">
                <h4><b><?= htmlspecialchars($doctor['dname']) ?></b></h4>
                <p><b>ID: </b><?= htmlspecialchars($doctor['sid']) ?></p>
                <p><b>Address: </b><?= htmlspecialchars($doctor['address']) ?></p>
                <p><b>Phone: </b><?= htmlspecialchars($doctor['phno']) ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
