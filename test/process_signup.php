<?php
// session_start();

// Include the database connection file
include '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    extract($_POST);
// File upload handling
$targetDirectory = '../image';

$targetFile = $targetDirectory . basename($_FILES["profile_image"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["profile_image"]["tmp_name"]);
if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    echo "File is not an image.";
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["profile_image"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // exit the script if file upload fails
    exit;
} else {
    // if everything is ok, try to upload file
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
        echo "The file " . basename($_FILES["profile_image"]["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
        // exit the script if file upload fails
        exit;
    }
}
    // Check if the username already exists in the database
    $checkUsernameQuery = "SELECT COUNT(*) as count FROM admin_login WHERE username = :username";
    $checkUsernameStmt = $pdo->prepare($checkUsernameQuery);
    $checkUsernameStmt->bindValue(':username', $username);
    $checkUsernameStmt->execute();
    $usernameCount = $checkUsernameStmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($usernameCount > 0) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Username already exists. Please choose a different username.')
            window.location.href='create_admin.php';
            </SCRIPT>");
        exit;
    }
        
    // Check if the password and retype password match
    if ($password !== $confirm_password) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Passwords do not match. Please try again.')
            window.location.href='create_admin.php';  
            </SCRIPT>");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   $sql = "INSERT INTO `admin_login` (`admin_id`, `profile_image`, `firstname`, `lastname`, `email`, `mobile`, `username`, `password`)
        VALUES (:admin_id, :profile_image, :firstname, :lastname, :email, :mobile, :username, :password)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":admin_id", generateAdminID($pdo));
    $stmt->bindValue(":profile_image", $profile_image); 
    $stmt->bindValue(":firstname", htmlspecialchars($firstname));
    $stmt->bindValue(":lastname", htmlspecialchars($lastname));
    $stmt->bindValue(":email", filter_var($email, FILTER_SANITIZE_EMAIL));
    $stmt->bindValue(":mobile", htmlspecialchars($mobile));
    $stmt->bindValue(":username", htmlspecialchars($username));
    $stmt->bindValue(":password", $hashed_password);

    $result = $stmt->execute();

    if ($result) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Admin user created successfully.')
            window.location.href='../index.php';
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