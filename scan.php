<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["currentuser"])) {
    die("Error: User not logged in.");
}

$guid = $_SESSION["currentuser"]; // Logged-in user ID

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch cart items for the user
$query = "SELECT product.pid, product.pname, product.price, orders.quantity, orders.sid 
          FROM orders 
          JOIN product ON orders.pid = product.pid
          WHERE orders.uid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $guid);
$stmt->execute();
$result = $stmt->get_result();

$totalAmount = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Home Page</title>
    <link rel="stylesheet" href="css/AddProduct.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<style>
        video { width: 30%;  }
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
                <a href="Homepage.php?id=<?= urlencode($guid) ?>">HOME</a>
                <a href="Buy.php?id=<?= urlencode($guid) ?>">BUY</a>
                <a href="Orders.php?id=<?= urlencode($guid) ?>">ORDERS</a>
                <a href="scan.php?id=<?= urlencode($guid) ?>">SCAN & ORDER</a>
            </div>
        </div>
    </div>
</div>
<!-- Checkout Header -->
<div class="container mt-4">
 <center><video id="preview"></video></center>
   
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

<div class="container mt-4">
<div class="bigcard">
            <div class="bigcard1">

    <!-- Cart Items Table -->
    <h4 >Cart Items</h4>
    <table class="table table-bordered" border="1" >
        <thead class="thead-dark">
            <tr>
                <th>Product No</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Seller ID</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): 
            $totalAmount += $row['price'] * $row['quantity'];
        ?>
            <tr>
                <td><?= htmlspecialchars($row['pid']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['sid']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h5 >Total Amount: <strong><?= $totalAmount ?></strong></h5>
 </div>
            <div class="bigcard2">
    <!-- Payment Section -->
    <div class="card p-4 mt-4">
        <h4>Pay Online!!</h4>
		
        <form action="ProcessPayment.php" method="post">
                <input type="text" name="pid" id="scan"  hidden required>
            <div class="form-group">
                <label>Enter Card Type</label>
                <select name="card_type" class="form-control">
                    <option value="Debit">Debit</option>
                    <option value="Credit">Credit</option>
                </select>
            </div><br/>
            <div class="form-group">
                <label>Enter Card Number</label>
                <input type="text" name="card_number" class="form-control" required>
            </div><br/>
            <div class="form-group">
                <label>Enter Card Name</label>
                <input type="text" name="card_name" class="form-control" required>
            </div><br/>
            <div class="form-group">
                <label>Enter CVV</label>
                <input type="password" name="cvv" class="form-control" required>
            </div><br/>
            <div class="form-group">
                <label>Enter PIN</label>
                <input type="password" name="pin" class="form-control" required>
            </div><br/>
            <button type="submit" class="btn btn-success">Proceed to Pay</button>
        </form>
    </div>
</div>
 </div>
        </div>
<?php
$stmt->close();
$conn->close();
?>

</body>
</html>
