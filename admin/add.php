<?php

include '../process/connect.php';

    
        
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;


        
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    extract($_POST);

    // Check if the username already exists in the database
    $checkUsernameQuery = "SELECT COUNT(*) as count FROM admin_login WHERE username = :username";
    $checkUsernameStmt = $pdo->prepare($checkUsernameQuery);
    $checkUsernameStmt->bindValue(':username', $username);
    $checkUsernameStmt->execute();
    $usernameCount = $checkUsernameStmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($usernameCount > 0) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Username already exists. Please choose a different username.')
            window.location.href='../admin.php';
            </SCRIPT>");
        exit;
    }
        
    // Check if the password and retype password match
    if ($password !== $confirm_password) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Passwords do not match. Please try again.')
            window.location.href='../admin.php';  
            </SCRIPT>");
        exit;
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $status = 'active';

Configuration::instance([
    'cloud' => [
      'cloud_name' => 'djpkvzlai', 
      'api_key' => '221129169276994', 
      'api_secret' => '5QO6KwczMhxmWt2OAGxLg2dCJcE'],
      'url' => [
      'secure' => true]]);
// (new UploadApi())->upload('image/cert.jpg')
    $profile_image = $_FILES['profile_image']['tmp_name'];
    $result_profile_image = (new UploadApi())->upload($profile_image);
    $profile_image_url = $result_profile_image['secure_url'];

    $sql = "INSERT INTO `admin_login` (`firstname`, `lastname`, `email`, `mobile`, `username`, `password`, `status`, `profile_image`)
    VALUES (:firstname, :lastname, :email, :mobile, :username, :password, :status, :profile_image_url)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":firstname", htmlspecialchars($firstname));
    $stmt->bindValue(":lastname", htmlspecialchars($lastname));
    $stmt->bindValue(":email", filter_var($email, FILTER_SANITIZE_EMAIL));
    $stmt->bindValue(":mobile", htmlspecialchars($mobile));
    $stmt->bindValue(":username", htmlspecialchars($username));
    $stmt->bindValue(":password", $hashed_password);
    $stmt->bindValue(":status", $status);
    $stmt->bindValue(":profile_image_url", $profile_image_url);

    $result = $stmt->execute();

    if ($result) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Admin user created successfully.')
            window.location.href='../admin.php';
            </SCRIPT>");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
// $pdo = null;

}
?>