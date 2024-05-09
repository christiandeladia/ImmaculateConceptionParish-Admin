<?php 
include 'process/login.php';

// Check if login is active
if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the function to check login status
    $login_status = checkLoginStatus($username, $password);

    if($login_status === "active") {
        // Redirect to admin page if login is active
        header("location: admin.php");
        exit;
    } elseif($login_status === "inactive") {
        $error_message = "Your account is inactive. Please contact the administrator.";
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link rel="stylesheet" href="style/login.css">
    <link rel="icon" type="image/x-icon" href="image/admin.ico">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<!-- <body>
    <h2>Admin Login</h2>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body> -->

<body>
    <div id="form-main-wrapper">
        <div class="form-container">
            <div class="admin-avtar">
                <h1>Admin Login</h1>
            </div>
            <div class="form-wrapper">
                <div class="form-con form">
                    <?php if (isset($error_message)) : ?>
                    <p><?php echo $error_message; ?></p>
                    <?php endif; ?>
                    <form method="post">
                        <div class="field-con">
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" placeholder="username.example" required>
                        </div>
                        <div class="field-con">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="**********" required>
                        </div>

                        <div class="flex form-btn-con">
                            <!-- < class="remember-me">
                                <input type="checkbox" name="remember-me" id="rem-me" /> <label
                                    for="rem-me"><span>Remember Me</span></label>
                            </span> -->
                            <span class="remember-me">
                                <input id="check" type="checkbox" checked />
                                <label for="checkbox"> I agree to <a href="#">Terms and Conditions</a>.</label>
                            </span>

                            <div class="sub-btn-wrap">
                                <input type="submit" class="form-submit" name="submit" id="btncheck" value="login" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="add-links">
                <a href="thesis/index.php" title="Back to FoodWeb Homepage">Back to Site</a>
            </div>
        </div>


    </div>
</body>
<script src="js/checkbox.js"></script>

</html>