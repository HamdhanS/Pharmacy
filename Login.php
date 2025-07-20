<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid1 = isset($_POST['userid']) ? $_POST['userid'] : '';
    $pass1 = isset($_POST['password']) ? $_POST['password'] : '';
    $u2 = isset($_POST['utype']) ? $_POST['utype'] : '';
    $u = (int)$u2;

    $query = $u == 2 ? "SELECT sid, pass FROM Seller WHERE sid=?" : "SELECT uid, pass FROM customer WHERE uid=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $uid1);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_uid, $db_pass);
            $stmt->fetch();
            
            if ($db_pass === $pass1) {
                $_SESSION['currentuser'] = $uid1;
                $_SESSION['usertype'] = $u;
                
                $stmt->close();
                $conn->close();
                
                if ($u == 1) {
                    header("Location: Homepage.php");
                } else if ($u == 2) {
                    header("Location: SellerHomepage.php");
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password!'); window.location.href='Login.php';</script>";
            }
        } else {
            echo "<script>alert('User not found!'); window.location.href='Login.php';</script>";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Login</title>
<link rel="stylesheet" href="css/Login.css">
</head>
<body>
                <form name="form1" method="post" action="" onsubmit="return validateForm();">
	<div class="container">
		<div class="loginbox">
			<h2>Login</h2>
				<div class="customselect">
					<div class="select">
  					<select  name="utype" required>
    					<option value="" selected disabled>Select Account Type</option>
    					<option value="1">Customer</option>
    					<option value="2">Seller</option>
    					
  					</select>
					</div>
				</div>
			<input type="text" name="userid" placeholder="Enter User ID" required>
			<input type="password" name="password" placeholder="Enter Password" required>
			<input type="submit" value="Login">
		</div>
	</div>
	</form>
</body>
</html>