<?php
// Include necessary files and configurations
include '../process/connect.php';
// Check if admin is logged in
if (!isset($_SESSION['auth_admin'])) {
    // Redirect if not logged in
    header("location: ../admin.php");
    exit;
}

// Check if admin ID is provided via POST
if(isset($_POST['admin_id'])) {
    // Sanitize the input
    $admin_id = intval($_POST['admin_id']); // Assuming admin ID is an integer

    // Update the status of the administrator to 'inactive'
    $sql = "UPDATE admin_login SET status = 'active' WHERE admin_id = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Deactivation successful
        echo "Admin deactivated successfully.";
    } else {
        // Error occurred
        echo "Error deactivating admin.";
    }
} else {
    // Admin ID not provided
    echo "Admin ID not provided.";
}
?>