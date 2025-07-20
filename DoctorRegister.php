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
    $dname = trim($_POST["name"]);
    $phno = trim($_POST["phno"]);
    $address = trim($_POST["address"]);
    $sid = trim($_POST["uid"]);
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];

    // Validate passwords match
    if ($pass1 !== $pass2) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // Check if doctor ID already exists
        $checkDoctor = $conn->prepare("SELECT sid FROM doctor WHERE sid = ?");
        $checkDoctor->bind_param("s", $sid);
        $checkDoctor->execute();
        $checkDoctor->store_result();

        if ($checkDoctor->num_rows > 0) {
            echo "<script>alert('Doctor ID already exists. Choose another.');</script>";
        } else {
            // Insert new doctor into database (storing plain text password)
            $stmt = $conn->prepare("INSERT INTO doctor (sid, pass, dname, address, phno) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $sid, $pass1, $dname, $address, $phno);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Redirecting to login...'); window.location='doclogin.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkDoctor->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Register Doctor</title>
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
    <form name="form1" method="post" action="DoctorRegister.php" onsubmit="return validateForm();">
        <div class="container">
            <div class="registerbox">
                <h2>Register New Doctor</h2>
                <input type="text" name="name" placeholder="Enter Name" required>
                <input type="text" name="phno" placeholder="Enter Phone Number" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" required>
                <input type="text" name="address" placeholder="Enter Address" required>
                <input type="text" name="uid" placeholder="Enter Doctor ID" required>
                <input type="password" name="pass1" placeholder="Enter Password" required>
                <input type="password" name="pass2" placeholder="Retype Password" required>
                <input type="submit" value="Submit">
            </div>
        </div>
    </form>
</body>
</html>
