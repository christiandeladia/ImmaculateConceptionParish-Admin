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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<style>
    .password-input {
  display: flex;
  align-items: center;
}

.toggle-password-icon {
  cursor: pointer;
  /* margin-left: 10px; */
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  padding-right: 12px;
  position: relative;
  z-index: 2;
  color:  black;
}
</style>
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
            
            <div class="form-wrapper">
                <div class="form-con form">
                <div class="admin-avtar">
                <h1>Admin Login</h1>
            </div>
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
                            <span class="fas fa-eye-slash toggle-password-icon" id="togglePassword"></span>
                        </div>
                        <div class="field-con" style="padding-left: 5px;">
                        <div class="g-recaptcha" data-sitekey="6LdHhWkpAAAAANoFPNxXANeCUcRXtKfUrQ-Icdez"></div>
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
                                <input type="submit" class="form-submit" name="submit" id="btncheck" value="Login" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="add-links">
                <!-- <a href="thesis/index.php" title="Back to FoodWeb Homepage">Back to Site</a> -->
            </div>
        </div>


    </div>
</body>
<script src="js/checkbox.js"></script>
<script>
    const passwordField = document.getElementById("password");
    const togglePasswordIcon = document.getElementById("togglePassword");

    togglePasswordIcon.addEventListener("click", function() {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            togglePasswordIcon.classList.remove("fa-eye-slash");
            togglePasswordIcon.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            togglePasswordIcon.classList.remove("fa-eye");
            togglePasswordIcon.classList.add("fa-eye-slash");
        }
    });
</script>
<script>
    $(document).on('click', '#btncheck', function(event) {
        var response = grecaptcha.getResponse();
        if (response.length == 0) {
            alert("Please Verify you are not a robot");
            event.preventDefault(); // Prevent form submission
            return false;
        }
    });
</script>
</html>