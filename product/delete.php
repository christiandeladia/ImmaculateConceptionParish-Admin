<?php
  require_once "../process/connect.php";

$product_id = $_GET['product_id'];

$query = "DELETE FROM inventory WHERE product_id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$product_id]);

header('Location: ../products.php');
?>
