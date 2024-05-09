<?php
require_once '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you're passing the product ID via POST
    $productId = $_POST['product_id'];

    // Check the current product stock
    $queryCheckStock = "SELECT product_stock FROM inventory WHERE product_id = ?";
    $statementCheckStock = $pdo->prepare($queryCheckStock);
    $statementCheckStock->execute([$productId]);
    $stockResult = $statementCheckStock->fetch(PDO::FETCH_ASSOC);

    // If product stock is 0, update the status to "Out of Stock"; otherwise, update to "Available"
    $newStatus = ($stockResult['product_stock'] == 0) ? 'Out of Stock' : 'Available';

    // Update the status of the product
    $queryUpdateStatus = "UPDATE inventory SET status = ? WHERE product_id = ?";
    $statementUpdateStatus = $pdo->prepare($queryUpdateStatus);
    $statementUpdateStatus->execute([$newStatus, $productId]);

    // Optionally, you can check if the update was successful
    $rowCount = $statementUpdateStatus->rowCount();
    if ($rowCount > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the product status']);
    }
}
?>
