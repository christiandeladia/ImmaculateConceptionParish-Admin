<?php
// Include necessary files and configurations
include '../process/connect.php';
// Check if admin is logged in
if (!isset($_SESSION['auth_admin'])) {
    // Redirect if not logged in
    header("location: ../faq.php");
    exit;
}

// Check if admin ID is provided via POST
if(isset($_POST['faq_id'])) {
    // Sanitize the input
    $faq_id = intval($_POST['faq_id']); // Assuming admin ID is an integer

    // Update the status of the administrator to 'inactive'
    $sql = "UPDATE faq SET status = 'active' WHERE faq_id = :faq_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':faq_id', $faq_id, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Deactivation successful
        echo "FAQ activated successfully.";
    } else {
        // Error occurred
        echo "Error activating FAQ.";
    }
} else {
    // Admin ID not provided
    echo "FAQ ID not provided.";
}
?>