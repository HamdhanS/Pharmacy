<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["currentuser"])) {
    header("Location: login.php");
    exit();
}

// Get logged-in seller ID
$guid = $_SESSION["currentuser"];

// Database credentials
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

// Prepare SQL query
$query = "SELECT sname, sid, address, phno FROM seller WHERE sid=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $guid);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();

// Close connections
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Home Page</title>
<link rel="stylesheet" href="css/Homepage.css">
</head>
<body>
<div class="main">
    <div class="topbar1"></div>
    <div class="topbar2">
        <div class="container1">
            <div class="logout-btn">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="header">
        <div class="container2">
            <div class="navbar">
                <a href="SellerHomepage.php?id=<?= urlencode($guid) ?>">HOME</a>
                <a href="AddProduct.php?id=<?= urlencode($guid) ?>">ADD</a>
                <a href="AddInventory.php?id=<?= urlencode($guid) ?>">RESTOCK</a>
                <a href="SellerOrders.php?id=<?= urlencode($guid) ?>">ORDERS</a>
            </div>
        </div>
    </div>
</div>

<div class="active">
    <div class="filler"></div>
    <h2>Welcome, <?php echo htmlspecialchars($guid); ?></h2>

    <?php if ($seller): ?>
        <div class="filler2"></div>
        <div class="card">
            <img src="images/vendor.png" class="Avatar" width="264" height="194">
            <div class="container">
                <h4><b><?php echo htmlspecialchars($seller["sname"]); ?></b></h4>
                <p><b>ID: </b><?php echo htmlspecialchars($seller["sid"]); ?></p>
                <p><b>Address: </b><?php echo htmlspecialchars($seller["address"]); ?></p>
                <p><b>Phone: </b><?php echo htmlspecialchars($seller["phno"]); ?></p>
            </div>
        </div>
    <?php else: ?>
        <p>Error: Seller details not found.</p>
    <?php endif; ?>
</div>
</body>
</html>
