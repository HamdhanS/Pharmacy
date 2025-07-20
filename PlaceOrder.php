<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["currentuser"])) {
    die("Error: User not logged in.");
}

$guid = $_SESSION["currentuser"]; // Logged-in user ID (customer ID)

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check that the user exists in the customer table (to satisfy foreign key constraint)
$queryCustomer = "SELECT uid FROM customer WHERE uid = ?";
$stmtCustomer = $conn->prepare($queryCustomer);
$stmtCustomer->bind_param("s", $guid);
$stmtCustomer->execute();
$resultCustomer = $stmtCustomer->get_result();
if ($resultCustomer->num_rows == 0) {
    die("Error: Logged-in user not found in customer table.");
}
$stmtCustomer->close();

// Get POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST["pid"];
    $qr = intval($_POST["orderquantity"]); // Order quantity

    // Query to fetch product details from inventory and product tables
    $query1 = "SELECT P.pid, O.sid, P.price 
               FROM inventory O 
               JOIN product P ON P.pid = O.pid 
               WHERE P.pid = ?";
    $stmt = $conn->prepare($query1);
    $stmt->bind_param("s", $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $a = $row["pid"];
        $b = $row["sid"];
        $c = $row["price"];

        // Calculate total price
        $total_price = $qr * $c;

        // Insert order into the orders table
        $query2 = "INSERT INTO orders (pid, sid, uid, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("sssii", $a, $b, $guid, $qr, $total_price);
        
        if ($stmt2->execute()) {
            // Redirect to checkout page on success
            header("Location: scan.php");
            exit();
        } else {
            echo "Error inserting order: " . $stmt2->error;
        }
        $stmt2->close();
    } else {
        echo "Error: Product not found.";
    }
    $stmt->close();
} else {
    die("Error: Invalid request method. Please submit the form.");
}

$conn->close();
?>
