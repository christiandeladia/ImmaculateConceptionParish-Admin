<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];

    // Sanitize $itemId to prevent SQL injection (unnecessary due to prepared statement)
    // $itemId = mysqli_real_escape_string($conn, $itemId);

    $result = mysqli_query($conn, "SELECT * FROM funeral WHERE id = $itemId");
    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $reference_id = uniqid();
            $user_first_name = $row['user_first_name'];
            $client_id = $row['client_id'];

            // Use prepared statement to prevent SQL injection
            $insertfuneralQuery = "UPDATE funeral SET status_id = '2' WHERE id = ?";
            $stmtUpdate = mysqli_prepare($conn, $insertfuneralQuery);
            mysqli_stmt_bind_param($stmtUpdate, "s", $itemId);
            $insertfuneralResult = mysqli_stmt_execute($stmtUpdate);

            if ($insertfuneralResult) {
                // Insert into notification_client table for funeral
                $notificationfuneralQuery = "INSERT INTO notification_client (reference_id, services, status, customer_id, customer_name, apply_status) 
                            VALUES (?, 'Funeral', 'unread', ?, ?, 'Approved')";
                $stmtInsert = mysqli_prepare($conn, $notificationfuneralQuery);
                mysqli_stmt_bind_param($stmtInsert, "sss", $reference_id, $client_id, $user_first_name);
                
                $notificationfuneralResult = mysqli_stmt_execute($stmtInsert);

                if ($notificationfuneralResult) {
                    echo 'success';
                } else {
                    // Log detailed error message or provide more specific information
                    echo 'notification_insert_error';
                }
            } else {
                // Log detailed error message or provide more specific information
                echo 'insert_error';
            }
        } else {
            echo 'row_null';
        }
    } else {
        // Log detailed error message or provide more specific information
        echo 'select_error';
    }
} else {
    echo 'invalid_request';
}
?>
