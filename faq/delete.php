<?php
  require_once "../process/connect.php";

$faqid = $_GET['faq_id'];

$query = "DELETE FROM faq WHERE faq_id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$faqid]);

header('Location: ../faq.php');
?>
