<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["currentuser"])) {
    die("Error: User not logged in.");
}

$cid = $_SESSION["currentuser"]; // Logged-in Customer ID

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_type = $_POST["card_type"];
    $card_number = $_POST["card_number"];
    $card_name = $_POST["card_name"];
    $cvv = $_POST["cvv"];
    $pin = $_POST["pin"];
    
    // Fetch total amount from cart for the user
    $query = "SELECT SUM(product.price * orders.quantity) AS total 
              FROM orders 
              JOIN product ON orders.pid = product.pid 
              WHERE orders.uid = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalAmount = $row["total"] ?? 0; // Default to 0 if no cart items

    // Insert payment details into `payment` table
    $insertQuery = "INSERT INTO payment (cid, total, cardtype, cardno, cardname, cvv, pin) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("idssssi", $cid, $totalAmount, $card_type, $card_number, $card_name, $cvv, $pin);

    if ($stmt->execute()) {
        echo "<script>alert('Payment Successful!'); window.location.href='Homepage.php';</script>";
    } else {
        echo "<script>alert('Payment Failed. Please try again.'); window.location.href='Checkout.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
