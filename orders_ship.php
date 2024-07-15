
<?php
// Include your database connection code
require "process/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["groupId"])) {
    $groupId = $_POST["groupId"];
    
    // Update status to 2 for the specified group order ID
    $query = "UPDATE orders SET status = 2 WHERE group_order = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        // Check for errors in preparing the statement
        echo "Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $groupId);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
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
            $notificationQuery = "INSERT INTO notification_order (order_status, status, group_order, customer_id, customer_name) 
                                  VALUES ('2', 'unread', ?, ?, ?)";
            $stmtInsert = mysqli_prepare($conn, $notificationQuery);
            mysqli_stmt_bind_param($stmtInsert, "sss", $groupId, $customer_id, $first_name);
            $notificationResult = mysqli_stmt_execute($stmtInsert);

            if ($notificationResult) {
                echo "success";
            } else {
                echo "notification_insert_error";
            }
        } else {
            echo "error";
        }

        mysqli_stmt_close($stmt);
    }
} else {
    echo "Invalid request";
}

?>
