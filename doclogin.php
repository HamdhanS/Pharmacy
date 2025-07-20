<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid1 = isset($_POST['userid']) ? trim($_POST['userid']) : '';
    $pass1 = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Query to check doctor credentials
    $query = "SELECT sid, pass FROM doctor WHERE sid=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $uid1);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_uid, $db_pass);
            $stmt->fetch();
          
            // Use password_verify for security (if passwords are hashed)
            if ($pass1 == $db_pass) { 
                $_SESSION['currentuser'] = $uid1; // Store doctor ID in session
              
                $stmt->close();
                $conn->close();
                
                // Redirect to doctor dashboard
              header("Location: DoctorHomepage.php");
                exit();
            } else {
            echo "<script>alert('Incorrect password!'); window.location.href='doclogin.php';</script>";
			 
            }
        } else {
            echo "<script>alert('Doctor not found!'); window.location.href='doclogin.php';</script>";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Login</title>
    <link rel="stylesheet" href="css/Login.css">
</head>
<body>

    <form name="doctor_login" method="post" action="doclogin.php">
        <div class="container">
            <div class="loginbox">
                <h2>Doctor Login</h2>
                <input type="text" name="userid" placeholder="Enter Doctor ID" required>
                <input type="password" name="password" placeholder="Enter Password" required>
                <input type="submit" value="Login">
            </div>
        </div>
    </form>

</body>
</html>
