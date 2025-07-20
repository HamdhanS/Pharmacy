<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["uid"])) {
    // Redirect to the login page if not logged in
    header("Location: index.php");
    exit();
}

// Step 1: Destroy the Session
session_destroy();

// Step 2: Redirect to the Login Page
header("Location: index.php");
exit();
?>
