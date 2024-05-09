<?php
require "connect.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {

    extract($_POST);

    $stmt = $pdo->prepare("SELECT * FROM `admin_login` WHERE `username` = ?");
    $stmt->execute([$username]); // Try both mobile number and email as the username
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($password, $result['password'])) {
        // Check if the admin's status is active
        if ($result['status'] == 'active') {
            // Admin login is successful, store admin details in session
            $_SESSION['auth_admin'] = $result;
        } else {
            // Admin status is inactive, redirect with an error message
            echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Your account is inactive. Please contact the administrator.')
            window.location.href='./index.php';
            </SCRIPT>");
            exit; // Stop further execution
        }
    } else {
        // Invalid username or password
        echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Invalid username or password')
        window.location.href='./index.php';
        </SCRIPT>");
        exit; // Stop further execution
    }
}

if (isset($_SESSION['auth_admin'])) {
    // If admin is already logged in, redirect to dashboard
    header("location: dashboard.php");
    exit;
}
?>





