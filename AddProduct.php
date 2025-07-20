<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['currentuser'])) {
    header("Location: Login.php");
    exit();
}

// Get the logged-in user ID
$id2 = $_SESSION['currentuser'];

// Database connection credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drugdatabase";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (!isset($_POST['prname'], $_POST['prid'], $_POST['mfname'], $_POST['mdate'], $_POST['edate'], $_POST['price'], $_POST['quantity'])) {
        die("Error: All fields are required.");
    }

    // Retrieve form data
    $prname = $_POST['prname'];
    $prid = $_POST['prid'];
    $mfname = $_POST['mfname'];
    $mdate = $_POST['mdate'];
    $edate = $_POST['edate'];
    $price = intval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    // Check if the product already exists
    $query1 = "SELECT pid FROM product WHERE pid = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("s", $prid);
    $stmt1->execute();
    $result = $stmt1->get_result();

    if ($result->num_rows == 0) {
        // Insert into 'product' table
        $query2 = "INSERT INTO product (pid, pname, manufacturer, mfg, exp, price) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("sssssi", $prid, $prname, $mfname, $mdate, $edate, $price);
        $stmt2->execute();

        // Insert into 'inventory' table
        $query3 = "INSERT INTO inventory (pid, pname, sid, quantity) VALUES (?, ?, ?, ?)";
        $stmt3 = $conn->prepare($query3);
        $stmt3->bind_param("sssi", $prid, $prname, $id2, $quantity);
        $stmt3->execute();

        // Redirect to AddInventory page after successful addition
        echo "<script>
            alert('Product Registered Successfully!');
            window.location.href='AddInventory.php?id=" . urlencode($id2) . "';
        </script>";
        exit();
    } else {
        // Redirect if product already exists
        echo "<script>
            alert('Product ID already exists!');
            window.location.href='AddProductError.html';
        </script>";
        exit();
    }

    // Close statements
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Add Product</title>
<link rel="stylesheet" href="css/AddProduct.css">
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
				<a href="SellerHomepage.php?id=<?= urlencode($id2) ?>">HOME</a>
				<a href="AddProduct.php?id=<?= urlencode($id2) ?>">ADD</a>
				<a href="AddInventory.php?id=<?= urlencode($id2) ?>">RESTOCK</a>
				<a href="SellerOrders.php?id=<?= urlencode($id2) ?>">ORDERS</a>
			</div>
		</div>
	</div>
</div>
<div class="active">
	<div class="filler"></div>
		<h2 style="padding-left:100px;">Add product</h2>
		<form action="AddProduct.php?id=<?= htmlspecialchars($id2) ?>" method="post">
			<div class="bigcard">
				<div class="bigcard1">
					<h3>Enter Product Name</h3><input type='text' name='prname' required>
					<h3>Enter Product ID</h3><input type='text' name='prid' required>
					<h3>Enter Manufacturer Name</h3><input type='text' name='mfname' required>
					<h3>Enter Manufacture Date</h3><input type="text" name="mdate" placeholder="YYYY-MM-DD" onkeypress="return event.charCode>= 48 && event.charCode<= 57 || event.charCode==45" required>
					<h3>Enter Expiry Date</h3><input type="text" name="edate" placeholder="YYYY-MM-DD" onkeypress="return ((event.charCode>= 48 && event.charCode<= 57) || event.charCode==45)" required><br/>
    		  	</div>
  		    	<div class="bigcard2">
   					 <h3>Quantity</h3><input type="text" name="quantity" onkeypress="return event.charCode>= 48 && event.charCode<= 57" required>
   					 <h3>Price</h3><input type="text" name="price" onkeypress="return event.charCode>= 48 && event.charCode<= 57" required>
   					 <p></p>
 					 <input type="submit" value="Add">
				</div>
			</div>
		</form>
	</div>
</body>
</html>