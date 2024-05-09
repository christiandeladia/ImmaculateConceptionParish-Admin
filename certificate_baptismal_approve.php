<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['itemId'])){
        $itemId = $_POST['itemId'];

        $result = mysqli_query($conn, "SELECT * FROM binyag_request_certificate WHERE id = $itemId");

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            // Retrieving additional data from POST request
            $baptismal_date = $_POST["baptismal_date"];
            $baptized_by = $_POST["baptized_by"];
            $godfather = $_POST["godfather"];
            $godmother = $_POST["godmother"];

            $reference_id = uniqid();
            $status_id = "2";

            // Use UPDATE query to update existing record including additional fields
            $updateQuery = "UPDATE binyag_request_certificate SET status_id=?, baptismal_date=?, baptized_by=?, godfather=?, godmother=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ssssss", $status_id, $baptismal_date, $baptized_by, $godfather, $godmother, $itemId);
            $updateResult = mysqli_stmt_execute($stmt);

            if ($updateResult) {
                // Insert into notification_Completed table
                $notificationQuery = "INSERT INTO notification_client (reference_id, services, status, customer_id, customer_name, apply_status) 
                VALUES (?, 'Binyag Request Certificate', 'unread', ?, ?, 'Approved')";
                $stmt = mysqli_prepare($conn, $notificationQuery);
                mysqli_stmt_bind_param($stmt, "sss", $reference_id, $row['client_id'], $row['user_first_name']);
                $notificationResult = mysqli_stmt_execute($stmt);

                if ($notificationResult) {
                    echo 'success'; // Return success message
                } else {
                    echo 'error inserting notification'; // Return error message
                }
            } else {
                echo 'error updating record'; // Return error message
            }
        } else {
            echo 'error fetching record'; // Return error message
        }
    } else {
        echo 'error: itemId is not set'; // Return error message
    }
} else {
    echo 'error: invalid request method'; // Return error message
}
?>
