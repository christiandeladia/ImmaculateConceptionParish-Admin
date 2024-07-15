<?php
require "process/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Delete from database
    $delete_sql = "DELETE FROM schedule WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);

    if ($delete_stmt) {
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);

        if ($delete_result) {
            echo "Schedule deleted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error in preparing SQL statement: " . mysqli_error($conn);
    }
}
?>
