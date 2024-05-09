<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['itemId'])){
        $itemId = $_POST['itemId'];

        $result = mysqli_query($conn, "SELECT * FROM binyag_request_certificate WHERE id = $itemId");

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            $reference_id = uniqid();
            $status_id = "3";

            // Use UPDATE query to update existing record
            $updateQuery = "UPDATE binyag_request_certificate SET status_id=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ss", $status_id, $itemId);
            $updateResult = mysqli_stmt_execute($stmt);

            if ($updateResult) {
                // Insert into notification_Completed table
                $notificationQuery = "INSERT INTO notification_client (reference_id, services, status, customer_id, customer_name, apply_status) 
                VALUES (?, 'Binyag Request Certificate', 'unread', ?, ?, 'Completed')";
                $stmt = mysqli_prepare($conn, $notificationQuery);
                mysqli_stmt_bind_param($stmt, "sss", $reference_id, $row['client_id'], $row['user_first_name']);
                $notificationResult = mysqli_stmt_execute($stmt);

                if ($notificationResult) {
                    // Notification inserted successfully
                    echo 'success';
                } else {
                    // Error inserting notification
                    echo 'error inserting notification';
                }
            } else {
                // Error updating record
                echo 'error updating record';
            }
        } else {
            // Error fetching record
            echo 'error fetching record';
        }
    } else {
        // itemId is not set
        echo 'error: itemId is not set';
    }
} else {
    // Invalid request method
    echo 'error: invalid request method';
}
?>
