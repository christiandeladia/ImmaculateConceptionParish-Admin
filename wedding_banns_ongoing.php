<?php
include 'process/connect.php';
if (!isset($_SESSION['auth_admin'])) {
    header("location: wedding_banns.php");
    exit;
}

if(isset($_POST['id'])) {
    $id = intval($_POST['id']); 

    $sql = "UPDATE wedding_banns SET status = 'ongoing' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Wedding Banns Ongoing successfully.";
    } else {
        echo "Error Wedding Banns Ongoing.";
    }
} else {
    echo " ID not provided.";
}
?>