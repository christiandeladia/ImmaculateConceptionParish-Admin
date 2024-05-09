<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['dataId'])){
        $dataId = $_POST['dataId'];

        $result = mysqli_query($conn, "SELECT * FROM funeral WHERE id = $dataId");

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            $reference_id = uniqid();
            $reason = $_POST["reason"];
            $remarks = $_POST["remarks"];
            $status_id = "4";

            // Use UPDATE query to update existing record
            $updateQuery = "UPDATE funeral SET reason=?, remarks=?, status_id=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ssss", $reason, $remarks, $status_id, $dataId);
            $updateResult = mysqli_stmt_execute($stmt);

            if ($updateResult) {
                // Insert into notification_decline table
                $notificationQuery = "INSERT INTO notification_client (reference_id, services, status, customer_id, customer_name, apply_status, reason, remarks) 
                VALUES (?, 'Funeral', 'unread', ?, ?, 'Decline', ?, ?)";
                $stmt = mysqli_prepare($conn, $notificationQuery);
                mysqli_stmt_bind_param($stmt, "sssss", $reference_id, $row['client_id'], $row['user_first_name'], $reason, $remarks);
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
        // DataId is not set
        echo 'error: dataId is not set';
    }
} else {
    // Invalid request method
    echo 'error: invalid request method';
}
?>
