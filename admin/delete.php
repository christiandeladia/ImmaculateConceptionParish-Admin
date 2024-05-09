<?php
  require_once "../process/connect.php";

$admin_id = $_GET['admin_id'];

$query = "DELETE FROM admin_login WHERE admin_id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$admin_id]);

header('Location: ../admin.php');
?>
