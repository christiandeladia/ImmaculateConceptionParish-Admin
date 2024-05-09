

<?php
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "thesis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'orders' table based on 'group_order'
$sql = "SELECT * FROM orders ORDER BY group_order, id";
$result = $conn->query($sql);

// Store data in an associative array grouped by 'group_order'
$data = array();
while ($row = $result->fetch_assoc()) {
    $group_order = $row['group_order'];
    if (!isset($data[$group_order])) {
        $data[$group_order] = array();
    }
    $data[$group_order][] = $row;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grouped Orders</title>
</head>
<body>

<h1>Grouped Orders</h1>

<?php
// Display the grouped orders
foreach ($data as $group_order => $orders) {
    echo "<p>Group Order: $group_order</p>";
    echo "<ul>";
    foreach ($orders as $order) {
        echo "<li>{$order['product_name']} - Quantity: {$order['product_quantity']}</li>";
    }
    echo "</ul>";
}
?>

</body>
</html>

