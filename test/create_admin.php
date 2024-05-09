<?php
require "../process/connect.php";
if (!isset($_SESSION['auth_admin'])) {
    header("location: index.php");
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
</head>

<body>
    <h2>Admin Sign Up</h2>
    <form action="process_signup.php" method="post" enctype="multipart/form-data">

        <label for="profile_image">Profile Image:</label>
        <input type="file" name="profile_image" accept="image/*"><br>

        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="mobile">Mobile Number:</label>
        <input type="text" name="mobile" required><br>

        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="confirm_password">Re-type Password:</label>
        <input type="password" name="confirm_password" required><br>

        <input type="submit" name="submit" value="Sign Up">
    </form>
</body>

</html>