<?php
require "process/connect.php";

if (!isset($_SESSION['auth_admin'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

// Update all notifications to 'unread'
$query = "UPDATE notification SET status = 'read'";
$result = mysqli_query($conn, $query);

if (!$result) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error marking all notifications as unread: " . mysqli_error($conn);
    exit;
}

echo "All notifications marked as unread successfully.";
?>
