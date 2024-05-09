<?php
require_once '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you're passing the product ID via POST
    $productId = $_POST['product_id'];

    // Update the status of the product to "Unlisted"
    $query = "UPDATE inventory SET status = 'Unlisted' WHERE product_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$productId]);

    // Optionally, you can check if the update was successful
    $rowCount = $statement->rowCount();
    if ($rowCount > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to unlist the product']);
    }
}
?>
