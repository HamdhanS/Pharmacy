<?php
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

session_start();
$gid = isset($_SESSION['currentuser']) ? $_SESSION['currentuser'] : null;
// Check if user is logged in
if (!isset($_SESSION['currentuser'])) {
    header("Location: Login.php"); // Redirect to login page if not logged in
    exit();
}

$guid = $_SESSION['currentuser']; // Get logged-in seller's ID
if (!$gid) {
    die("Error: User not logged in.");
}

// Fetch orders from stored procedure
$query = "CALL getsellerorders(?)";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $gid);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Free result set and close statement
$stmt->free_result();
$stmt->close();

// Clear any remaining results from stored procedure
while ($conn->more_results()) {
    $conn->next_result();
}

// Fetch quick orders
$quickOrderQuery = "SELECT * FROM quickorder";
$quickOrdersResult = $conn->query($quickOrderQuery);

if (!$quickOrdersResult) {
    die("Quick Order Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="css/Orders.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: Arial; }
        .tablink { background-color: #555; color: white; float: left; border: none; outline: none; cursor: pointer; padding: 14px 16px; font-size: 17px; width: 50%; }
        .tablink:hover { background-color: #777; }
        .tabcontent { display: none; padding: 20px; }
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
<button class="tablink" onclick="openPage('OrdersByBuy', this, 'red')">Orders by Buy</button>
<button class="tablink" onclick="openPage('Orders2', this, 'red')" id="defaultOpen">Orders by Quick Purchase</button>

<div id="OrdersByBuy" class="tabcontent" style="margin-top:90px;">
    <h2>Orders by Buy</h2>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Product ID</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Customer ID</th>
            <th>View Seller Details</th>
        </tr>

        <?php
        // Prepare a query to fetch only orders where `uid` matches the logged-in user
        $sql = "SELECT * FROM orders WHERE uid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $gid); // $gid is the session user ID
        $stmt->execute();
        $result = $stmt->get_result();

        while ($order = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['oid']); ?></td>
                <td><?php echo htmlspecialchars($order['pid']); ?></td>
                <td><?php echo htmlspecialchars($order['price']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['uid']); ?></td>
                <td><a href="cinf1.php?sid=<?php echo htmlspecialchars($order['uid']); ?>">View</a></td>
            </tr>
        <?php endwhile;

        $stmt->close(); // Close statement
        ?>
    </table>
</div>


<div id="Orders2" class="tabcontent" style="margin-top:90px;">
    <h2>Orders by Quick Purchase</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Customer ID</th>
            <th>Medicines</th>
            <th>View Customer Details</th>
        </tr>

        <?php
        // Prepare a query to fetch only the orders where `cid` matches the logged-in user
        $sql = "SELECT * FROM quickorder WHERE cid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $gid); // $gid is the session user ID
        $stmt->execute();
        $quickOrdersResult = $stmt->get_result();

        while ($quickOrder = $quickOrdersResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($quickOrder['id']); ?></td>
                <td><?php echo isset($quickOrder['cid']) ? htmlspecialchars($quickOrder['cid']) : "N/A"; ?></td>
                <td><?php echo htmlspecialchars($quickOrder['msg']); ?></td>
                <td><a href="cinf1.php?sid=<?php echo htmlspecialchars($quickOrder['cid']); ?>">View</a></td>
            </tr>
        <?php endwhile;

        $stmt->close(); // Close the statement
        ?>
    </table>
</div>


<script>
function openPage(pageName, elmnt, color) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
    }
    document.getElementById(pageName).style.display = "block";
    elmnt.style.backgroundColor = color;
}
document.getElementById("defaultOpen").click();
</script>

</body>
</html>

<?php
$conn->close();
?>
