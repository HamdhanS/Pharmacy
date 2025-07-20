<?php
session_start();

if (!isset($_SESSION['currentuser'])) {
    header("Location: Login.php");
    exit();
}
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
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$guid = $_SESSION['currentuser'];
$query = "SELECT fname, uid, address, phno, email FROM customer WHERE uid=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $guid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Home Page</title>
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
    <h2>Welcome <?php echo htmlspecialchars($guid); ?></h2>
    
    <?php if ($user): ?>
    <div class="filler2"></div>
    <div class="card">
        <img src="images/User.png" class="Avatar" width=234 height=234>
        <div class="container">
            <div class="space1"><b><?php echo htmlspecialchars($user['fname']); ?></b></div>
            <div class="filler3"></div>
            <div class="space"><b>ID: </b><?php echo htmlspecialchars($user['uid']); ?></div>
            <div class="space"><b>Address: </b><?php echo htmlspecialchars($user['address']); ?></div>
            <div class="space"><b>Phone: </b><?php echo htmlspecialchars($user['phno']); ?></div>
            <div class="space"><b>Email: </b><?php echo htmlspecialchars($user['email']); ?></div>
        </div>
    </div>
    <?php else: ?>
    <p>User data not found.</p>
    <?php endif; ?>
</div>

<button class="open-button" onclick="openForm()">Quick Purchase</button>
<div class="chat-popup" id="myForm">
    <form action="action_page.php" class="form-container" method="POST">
        <h1>Chat</h1>
        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="msg" required></textarea>
        <button type="submit" class="btn">Send</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
</div>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}
function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>
</body>
</html>
