<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Debug request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Error: Invalid request method. Please submit the form.");
}

// Debug form data
if (!isset($_POST["msg"]) || empty(trim($_POST["msg"]))) {
    die("Error: Order message is empty.");
}

// Debug session
if (!isset($_SESSION["currentuser"])) {
    die("Error: User not logged in.");
}

// Assign variables
$guid = $_SESSION["currentuser"];
$msg1 = trim($_POST["msg"]);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Debug before query execution
var_dump($guid, $msg1);

// Prepare and execute query
$stmt = $conn->prepare("INSERT INTO quickorder (cid, msg) VALUES (?, ?)");
if (!$stmt) {
    die("Error preparing SQL statement.");
}

$stmt->bind_param("ss", $guid, $msg1);
if ($stmt->execute()) {
    $_SESSION["successMsg"] = "Order placed successfully!";
    header("Location: Homepage.php");
    exit();
} else {
    die("Database error: " . $stmt->error);
}

// Close connection
$stmt->close();
$conn->close();
?>
