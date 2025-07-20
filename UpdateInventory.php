<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['currentuser'])) {
    header("Location: Login.php"); // Redirect to login page if not logged in
    exit();
}

// Get input values
$qt = intval($_POST['restock']); // Convert to integer
$prod = $_POST['pid'];
$guid = $_SESSION['currentuser']; // Logged-in seller ID

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update inventory query
$query = "UPDATE inventory SET quantity = quantity + ? WHERE sid = ? AND pid = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("iss", $qt, $guid, $prod);
    if ($stmt->execute()) {
        // Redirect to inventory page after update
		 echo "<script>
    alert('Registered Successfully!');
    window.location.href='AddInventory.php?id=" . urlencode($guid) . "';
</script>";
       
        exit();
    } else {
        echo "Error updating inventory: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing query: " . $conn->error;
}

// Close connection
$conn->close();
?>
