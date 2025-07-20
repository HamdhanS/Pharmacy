<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['currentuser'])) {
    header("Location: Login.php"); // Redirect to login page if not logged in
    exit();
}

$guid = $_SESSION['currentuser']; // Get logged-in seller's ID

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

// Query to fetch inventory details
$query = "SELECT p.pid, i.quantity, p.pname, p.manufacturer, p.mfg, p.exp, p.price 
          FROM product p, inventory i 
          WHERE p.pid = i.pid AND i.sid = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $guid);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="ISO-8859-1">
    <title>ReStock</title>
    <link rel="stylesheet" href="css/Buy.css">
    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 Columns */
            gap: 20px;
            padding: 20px;
        }
        .card {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .card h1 {
            font-size: 18px;
            margin: 10px 0;
        }
        .card p {
            font-size: 14px;
            color: #555;
        }
        .card input {
            width: 80%;
            padding: 5px;
            margin: 5px 0;
        }
        .card button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .card button:hover {
            background: #0056b3;
        }
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: repeat(2, 1fr); /* 2 Columns on Tablets */
            }
        }
        @media (max-width: 768px) {
            .container {
                grid-template-columns: repeat(1, 1fr); /* 1 Column on Mobile */
            }
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
                <a href="SellerHomepage.php?id=<?= urlencode($guid) ?>">HOME</a>
                <a href="AddProduct.php?id=<?= urlencode($guid) ?>">ADD</a>
                <a href="AddInventory.php?id=<?= urlencode($guid) ?>">RESTOCK</a>
                <a href="SellerOrders.php?id=<?= urlencode($guid) ?>">ORDERS</a>
            </div>
        </div>
    </div>
</div>

<div class="active">
    <div class="container">

    <?php
    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="card">
            <form action="UpdateInventory.php?id=<?= urlencode($guid) ?>" method="post">
                <img src="images/pills.png" alt="Medicine">
                <h1><?php echo htmlspecialchars($row["pname"]); ?></h1>
                <p><b>ID:</b> <?php echo htmlspecialchars($row["pid"]); ?></p>
                <p><b>Manufacturer:</b> <?php echo htmlspecialchars($row["manufacturer"]); ?></p>
                <p><b>Mfg Date:</b> <?php echo htmlspecialchars($row["mfg"]); ?></p>
                <p><b>Exp Date:</b> <?php echo htmlspecialchars($row["exp"]); ?></p>
                <p><b>Stock:</b> <?php echo htmlspecialchars($row["quantity"]); ?></p>
                <p><b>Price:</b> <?php echo htmlspecialchars($row["price"]); ?></p>
                <input type="text" name="restock" placeholder="Quantity" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($row["pid"]); ?>">
                <button>ReStock</button>
            </form>
        </div>
    <?php } ?>

    </div>
</div>

<?php
$stmt->close();
$conn->close();
?>

</body>
</html>
