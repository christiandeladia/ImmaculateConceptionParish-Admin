<?php
require "process/connect.php"; // Include your database connection file

// Check if the ID parameter is set in the POST request
if(isset($_POST['id'])) {
    $notificationId = $_POST['id'];

    // Update the status of the notification in the database to 'read'
    $query = "UPDATE notification SET status = 'read' WHERE id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$notificationId]);

    // Check if the update was successful
    if($statement->rowCount() > 0) {
        // Status updated successfully
        echo "Status updated successfully!";
    } else {
        // Failed to update status
        echo "Failed to update status!";
    }
} else {
    // ID parameter not set
    echo "ID parameter not set!";
}
?>
