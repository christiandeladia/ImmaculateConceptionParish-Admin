<?php
require_once '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faqid = $_POST['faq_id'];
    $titleFAQ = $_POST['title'];
    $contentFAQ = $_POST['content'];

    $status = "active"; 
    // Update the FAQ in the database
    $query = "UPDATE faq SET 
              title = ?, 
              content = ?, 
              status = ?
              WHERE faq_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$titleFAQ, $contentFAQ, $status, $faqid]);

    // Optionally, you can check if the update was successful and provide feedback to the user
    if ($statement->rowCount() > 0) {
        echo "FAQ updated successfully!";
    } else {
        echo "Failed to update FAQ.";
    }
}
?>
