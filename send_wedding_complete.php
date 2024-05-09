<?php
// Start the session
session_start();

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

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

// Check if the 'id' parameter is set in the URL
if (!isset($_GET["id"])) {
    echo "Error: ID parameter not set.";
    exit;
}

$id = $_GET["id"];
echo "ID: " . $id . "<br>"; // Debug output

// Fetch the data from the database based on the provided 'id'
$sql = "SELECT m.*, l.email AS user_email FROM `wedding` AS m
        INNER JOIN `login` AS l ON m.client_id = l.id
        WHERE m.`id` = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo "Error: Unable to prepare statement. " . mysqli_error($conn);
    exit;
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo "Error: Unable to fetch data from the database. " . mysqli_error($conn);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) {
    echo "Error: Unable to execute statement. " . mysqli_stmt_error($stmt);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    echo "Error: Unable to get result from statement. " . mysqli_stmt_error($stmt);
    exit;
}

$num_rows = mysqli_num_rows($result);
echo "Number of rows returned: " . $num_rows . "<br>"; // Debug output

if ($num_rows > 0) {
    $row = mysqli_fetch_assoc($result);

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

        $auth_firstname = isset($_SESSION['auth_admin']['firstname']) ? $_SESSION['auth_admin']['firstname'] : '';

        // Set email subject and body
        $mail->Subject = 'Wedding Application Completed';
        $mail->Body = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(#93C572, #FFFFFF);">
        <div style="background-color: #008000; text-align: center; padding: 10px;">
            <img src="https://res.cloudinary.com/dqtbveriz/image/upload/v1711791868/logo_white_lio37e.png" alt="Sample Logo" style="display: inline-block; max-width: 200px;">
        </div>
        <h2 style="color: #333333; font-size: 24px; font-weight: bold; text-align: center;"></h2>
        <p style="font-size: 16px;"><strong>Dear</strong> ' . $row["user_first_name"] . '</p>
        <p style="font-size: 16px;"><strong>We are pleased to inform you that your application for Wedding with ' . $row["reference_id"] . ' has been Completed.</strong></p>
        <p style="font-size: 16px;"><strong>Your request has been processed with careful consideration, and we are delighted <br>to extend our support for these significant milestones in your life and those of your loved ones.</strong></p>
        <p style="font-size: 16px;"><strong>Should you require any further assistance or have additional inquiries regarding the services provided, please feel free to contact our dedicated team. You can reach us by phone at [phone number] or via email at ' . $auth_email . '.</strong> </p>
        <p style="font-size: 16px;"><strong>We look forward to assisting you in making these occasions meaningful and memorable.</p>
        <p style="font-size: 16px;"><strong>Warm regards,</p>
        <p style="font-size: 16px;"><strong>' . $auth_firstname . '</p>
        <p style="font-size: 16px;">ICP</p>';
        $mail->AltBody = 'Request Submitted Submitted Successfully
        Dear ' . $row["user_first_name"] . '</p>
        We are pleased to inform you that your application for Wedding with ' . $row["reference_id"] . ' has been Completed.
        <br>
        Your request has been processed with careful consideration, and we are delighted <br>to extend our support for these significant milestones in your life and those of your loved ones
        <br>
        Should you require any further assistance or have additional inquiries regarding the services provided, please feel free to contact our dedicated team. You can reach us by phone at [phone number] or via email at ' . $auth_email . '.<br>
        We look forward to assisting you in making these occasions meaningful and memorable.
        <br>
        Warm regards,
        <br>
        ' . $auth_firstname . '
        <br>
        ICP';

        // Send the email
        if ($mail->send()) {
            // Display success message and redirect
            echo "<script>alert('Sent Successfully'); document.location.href = 'wedding.php';</script>";
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
?>
