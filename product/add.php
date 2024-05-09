<?php
require_once '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productPrice = $_POST['product_price'];
    $productDimension = $_POST['product_dimension'];
    $productStock = $_POST['product_stock'];
    $productImage = $_POST['product_image'];
    
    // Check if product stock is 0
    if ($productStock == 0) {
        $status = "Out of Stock";
    } else {
        $status = "Available";
    }

    $query = "INSERT INTO inventory (product_name, product_description, product_price, product_dimension, product_stock, product_image, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$productName, $productDescription, $productPrice, $productDimension, $productStock, $productImage, $status]);

    echo '<script>alert("Product Added Successfully!");</script>';
    echo '<script>window.location.href="../products.php";</script>';
}
?>
