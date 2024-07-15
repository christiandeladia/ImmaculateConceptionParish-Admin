<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["groupId"])) {
    // Start the session
    session_start();

    // Database configuration
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "icp_database";

    // Connect to the database
    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    // Check if the database connection is successful
    if (!$conn) {
        echo "Error: Database connection failed.";
        exit;
    }

    $groupId = $_POST["groupId"];

    $sql = "SELECT o.*, l.email AS user_email, l.first_name AS user_first_name FROM `orders` AS o
    INNER JOIN `login` AS l ON o.customer_id = l.id
    WHERE o.`group_order` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        echo "Error: Unable to prepare statement. " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $groupId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        echo "Error: Unable to fetch data from the database. " . mysqli_error($conn);
        exit;
    }
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        var_dump($row);     
        // Get the email address from the fetched data
        $email = $row["user_email"];

        // Check if the email address is not empty
        if (!empty($email)) {
            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'immaculate@devdojo.cloud';
            $mail->Password = 'immaculateEmail$123';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Set the sender email address
            $mail->setFrom('immaculate@devdojo.cloud');

            // Add the recipient email address
            $mail->addAddress($email);

            // Set email content as HTML
            $mail->isHTML(true);

            // Get the authenticated user's first name from the session
            $auth_firstname = isset($_SESSION['auth_admin']['firstname']) ? $_SESSION['auth_admin']['firstname'] : '';

            // Set email subject and body
            $mail->Subject = 'Order to Recieve';
            $mail->Body = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(#93C572, #FFFFFF);">
                <div style="background-color: #008000; text-align: center; padding: 10px;">
                    <img src="https://res.cloudinary.com/dqtbveriz/image/upload/v1711791868/logo_white_lio37e.png" alt="Sample Logo" style="display: inline-block; max-width: 200px;">
                </div>
                <h2 style="color: #333333; font-size: 24px; font-weight: bold; text-align: center;"></h2>
                <p style="font-size: 16px;"><strong>Hi! </strong> ' . $row["user_first_name"] . ' </p>
                <p style="font-size: 16px;"><strong>Great news! Your order ' . $row["group_order"] . ' has been shipped via J&T Express. Keep an eye out for delivery or pickup instructions.</strong></p>
                <p style="font-size: 16px;"><strong> Tracking number:  ' . $row["trackingNumber"] . '<br> You can track your order <a href="https://www.jtexpress.ph/trajectoryQuery?flag=1">here</a>.</strong></p>
                <p style="font-size: 16px;"><strong>Regards,</p>
                <p style="font-size: 16px;">ICP</p>';

            // Send the email
            if ($mail->send()) {
                // Display success message and redirect
                echo "<script>alert('Sent Successfully'); document.location.href = 'orders.php';</script>";
                exit;
            } else {
                // Display error message if email sending fails
                echo "Error: " . $mail->ErrorInfo;
                exit;
            }
        } else {
            // Display error message if the email address is empty
            echo "Error: Email address is empty.";
            exit;
        }
    } else {
        // Display error message if no rows are returned
        echo "Error: No data found for the provided ID.";
        exit;
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
