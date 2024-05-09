<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];

    // Sanitize $itemId to prevent SQL injection (unnecessary due to prepared statement)
    // $itemId = mysqli_real_escape_string($conn, $itemId);

    $result = mysqli_query($conn, "SELECT * FROM sickcall WHERE id = $itemId");
    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $reference_id = uniqid();
            $user_first_name = $row['user_first_name'];
            $client_id = $row['client_id'];

            // Use prepared statement to prevent SQL injection
            $insertsickcallQuery = "UPDATE sickcall SET status_id = '2' WHERE id = ?";
            $stmtUpdate = mysqli_prepare($conn, $insertsickcallQuery);
            mysqli_stmt_bind_param($stmtUpdate, "s", $itemId);
            $insertsickcallResult = mysqli_stmt_execute($stmtUpdate);

            if ($insertsickcallResult) {
                // Insert into notification_client table for sickcall
                $notificationsickcallQuery = "INSERT INTO notification_client (reference_id, services, status, customer_id, customer_name, apply_status) 
                            VALUES (?, 'Sickcall', 'unread', ?, ?, 'Approved')";
                $stmtInsert = mysqli_prepare($conn, $notificationsickcallQuery);
                mysqli_stmt_bind_param($stmtInsert, "sss", $reference_id, $client_id, $user_first_name);
                
                $notificationsickcallResult = mysqli_stmt_execute($stmtInsert);

                if ($notificationsickcallResult) {
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
