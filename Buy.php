<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["currentuser"])) {
    die("Error: User not logged in.");
}

$uid = $_SESSION["currentuser"]; // Logged-in user ID

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch product and inventory details
$query = "SELECT p.pname, p.pid, p.manufacturer, p.mfg, p.price, i.quantity 
          FROM product p
          JOIN inventory i ON p.pid = i.pid";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy</title>
    <link rel="stylesheet" href="css/Buy.css">
    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
        }
        .card img {
            width: 150px;
            height: 180px;
        }
        button {
            background: green;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        button:disabled {
            background: gray;
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
                <a href="Homepage.php">HOME</a>
                <a href="Buy.php">BUY</a>
                <a href="Orders.php">ORDERS</a>
                <a href="scan.php">SCAN & ORDER</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="images/pills.png">
                <h3><?= htmlspecialchars($row['pname']) ?></h3>
                <p><b>ID: </b><?= htmlspecialchars($row['pid']) ?></p>
                <p><b>Manufacturer: </b><?= htmlspecialchars($row['manufacturer']) ?></p>
                <p><b>Mfg Date: </b><?= htmlspecialchars($row['mfg']) ?></p>
                <p><b>Stock: </b><?= htmlspecialchars($row['quantity']) ?></p>
                <p><b>Price: </b>Rs.<?= htmlspecialchars($row['price']) ?></p>

                <?php if ($row['quantity'] > 0): ?>
                    <form action="PlaceOrder.php" method="post">
                        <input type="number" name="orderquantity" placeholder="Enter quantity" max="<?= htmlspecialchars($row['quantity']) ?>" required>
                        <input type="hidden" name="pid" value="<?= htmlspecialchars($row['pid']) ?>">
                        <button style="margin-top:20px;">Buy</button>
                    </form>
                <?php else: ?>
                    <button disabled>Out Of Stock</button>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</div>

<?php $conn->close(); ?>
</body>
</html>
