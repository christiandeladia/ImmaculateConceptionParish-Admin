<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['groupId']) && isset($_POST['reason'])) {
        $groupId = $_POST['groupId'];
        $reason = $_POST['reason'];

        // Check if the connection is established
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch the order details from the database
        $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE group_order = ?");
        mysqli_stmt_bind_param($stmt, "s", $groupId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Update the order status
            $status = "5"; // Assuming status 3 is for shipped orders

            // Use UPDATE query to update existing record
            $updateQuery = "UPDATE orders SET status=? WHERE group_order=?";
            $stmtUpdate = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmtUpdate, "ss", $status, $groupId);
            $updateResult = mysqli_stmt_execute($stmtUpdate);

            // Check if the update was successful
            if ($updateResult) {
                // Fetch user_first_name and customer_id from the login table
                $selectQuery = "SELECT login.first_name, orders.customer_id FROM orders 
                                INNER JOIN login ON orders.customer_id = login.id 
                                WHERE orders.group_order = ?";
                $stmtSelect = mysqli_prepare($conn, $selectQuery);
                mysqli_stmt_bind_param($stmtSelect, "s", $groupId);
                mysqli_stmt_execute($stmtSelect);
                mysqli_stmt_bind_result($stmtSelect, $first_name, $customer_id);
                mysqli_stmt_fetch($stmtSelect);
                mysqli_stmt_close($stmtSelect);

                // Insert into notification_order table
                $notificationQuery = "INSERT INTO notification_order (order_status, status, group_order, customer_id, customer_name, reason) 
                      VALUES ('5', 'unread', ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $notificationQuery);
                mysqli_stmt_bind_param($stmtInsert, "ssss", $groupId, $customer_id, $first_name, $reason);
                $notificationResult = mysqli_stmt_execute($stmtInsert);

                if ($notificationResult) {
                    echo 'success';
                } else {
                    echo 'error: notification insert failed';
                }
            } else {
                echo 'error: update failed';
            }

        } else {
            // Error fetching record or no record found
            echo 'error: no record found';
        }
    } else {
        // Required parameters are not set
        echo 'error: missing parameters';
    }
} else {
    // Invalid request method
    echo 'error: invalid request method';
}
?>
