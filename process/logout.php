<?php
session_start();

// Check if the user is already logged out
if (!isset($_SESSION['auth_admin'])) {
    // Redirect to login page
    header("Location: ../index.php");
    exit();
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit();
?>
