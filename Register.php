<?php
session_start();
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $phno = trim($_POST["phno"]);
    $address = trim($_POST["address"]);
    $uid = trim($_POST["uid"]);
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];

    // Validate passwords match
    if ($pass1 !== $pass2) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Check if user already exists
        $checkUser = $conn->prepare("SELECT uid FROM customer WHERE uid = ?");
        $checkUser->bind_param("s", $uid);
        $checkUser->execute();
        $checkUser->store_result();

        if ($checkUser->num_rows > 0) {
            echo "<script>alert('Customer ID already exists. Choose another.');</script>";
        } else {
            // Insert new customer into database (storing plain text password)
            $stmt = $conn->prepare("INSERT INTO customer (fname, lname, email, phno, address, uid, pass) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $fname, $lname, $email, $phno, $address, $uid, $pass1);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Redirecting to login...'); window.location='login.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkUser->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Register</title>
<link rel="stylesheet" href="css/Register.css">
<script>
function validateForm() {
    var pass1 = document.forms["form1"]["pass1"].value;
    var pass2 = document.forms["form1"]["pass2"].value;
    if (pass1 !== pass2) {
        alert("Passwords do not match!");
        return false;
    }
    return true;
}
</script>
</head>
<body>
    <form name="form1" method="post" action="register.php" onsubmit="return validateForm();">
        <div class="container">
            <div class="registerbox">
                <h2>Register New Customer</h2>
                <input type="text" name="fname" placeholder="Enter First Name" required>
                <input type="text" name="lname" placeholder="Enter Last Name" required>
                <input type="email" name="email" placeholder="Enter Email ID" required style="position: relative; border: none;	outline: none; height: 30px; border-bottom: 1px solid #808080; color: black; background: transparent; font-size:16px;">
                <input type="text" name="phno" placeholder="Enter Phone Number" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" required>
                <input type="text" name="address" placeholder="Enter Address" required>
                <input type="text" name="uid" placeholder="Enter Customer ID" required>
                <input type="password" name="pass1" placeholder="Enter Password" required>
                <input type="password" name="pass2" placeholder="Retype Password" required>
                <input type="submit" value="Submit">
            </div>
        </div>
    </form>
</body>
</html>
