<?php
  require_once "../process/connect.php";

$blogid = $_GET['blog_id'];

$query = "DELETE FROM blog WHERE blog_id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$blogid]);

header('Location: ../blog.php');
?>
