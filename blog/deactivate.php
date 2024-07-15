<?php
// Include necessary files and configurations
include '../process/connect.php';
// Check if admin is logged in
if (!isset($_SESSION['auth_admin'])) {
    // Redirect if not logged in
    header("location: ../blog.php");
    exit;
}

// Check if admin ID is provided via POST
if(isset($_POST['blog_id'])) {
    // Sanitize the input
    $blog_id = intval($_POST['blog_id']); // Assuming admin ID is an integer

    // Update the status of the administrator to 'inactive'
    $sql = "UPDATE blog SET status = 'inactive' WHERE blog_id = :blog_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Deactivation successful
        echo "blog deactivated successfully.";
    } else {
        // Error occurred
        echo "Error deactivating blog.";
    }
} else {
    // Admin ID not provided
    echo "blog ID not provided.";
}
?>