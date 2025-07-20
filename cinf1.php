<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['currentuser'])) {
    die("Error: User not logged in.");
}

$guid = $_SESSION['currentuser']; // Get the current user's ID
$id2 = isset($_GET['sid']) ? $_GET['sid'] : null; // Get seller ID from URL parameter

if (!$id2) {
    die("Error: No seller ID provided.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer details
$query = "SELECT fname, uid, address, phno, email FROM customer WHERE uid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id2);
$stmt->execute();
$result = $stmt->get_result();

$customer = $result->fetch_assoc();

// Close database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="ISO-8859-1">
    <title>Orders</title>
    <link rel="stylesheet" href="css/Orders.css">
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
                <a href="Homepage.php?id=<?= urlencode($guid) ?>">HOME</a>
                <a href="Buy.php?id=<?= urlencode($guid) ?>">BUY</a>
                <a href="Orders.php?id=<?= urlencode($guid) ?>">ORDERS</a>
                <a href="scan.php?id=<?= urlencode($guid) ?>">SCAN & ORDER</a>
            </div>
        </div>
    </div>
</div>

<div class="active">
    <div class="filler"></div>
    
	
    <h2>Customer Details </h2>
	
				<center><a href="Orders.php?id=<?= urlencode($guid) ?>">Go Back</a></center>
    
    <?php if ($customer): ?>
        <div class="filler2"></div>
        <div class="card">
            <img src="images/User.png" class="Avatar" width="234" height="234">
            <div class="container">
                <div class="space1"><b><?php echo htmlspecialchars($customer['fname']); ?></b></div>
                <div class="filler3"></div>
                <div class="space"><b>ID: </b><?php echo htmlspecialchars($customer['uid']); ?></div>
                <div class="space"><b>Address: </b><?php echo htmlspecialchars($customer['address']); ?></div>
                <div class="space"><b>Phone: </b><?php echo htmlspecialchars($customer['phno']); ?></div>
                <div class="space"><b>Email: </b><?php echo htmlspecialchars($customer['email']); ?></div>
            </div>
        </div>
    <?php else: ?>
        <p>No customer details found.</p>
    <?php endif; ?>
</div>

</body>
</html>
