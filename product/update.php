<?php
require_once '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productPrice = $_POST['product_price'];
    $productDimension = $_POST['product_dimension'];
    $productStock = $_POST['product_stock'];

    // Check if product stock is 0
    if ($productStock == 0) {
        $status = "Out of Stock";
    } else {
        $status = "Available";
    }

    // Update the product in the database
    $query = "UPDATE inventory SET 
              product_name = ?, 
              product_description = ?, 
              product_price = ?, 
              product_dimension = ?, 
              product_stock = ?, 
              status = ?
              WHERE product_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$productName, $productDescription, $productPrice, $productDimension, $productStock, $status, $productId]);

    // Optionally, you can check if the update was successful and provide feedback to the user
    if ($statement->rowCount() > 0) {
        echo "Product updated successfully!";
    } else {
        echo "Failed to update product.";
    }
}
?>
