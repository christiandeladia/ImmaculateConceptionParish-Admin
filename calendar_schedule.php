<?php
require 'process/connect.php';

$query = "SELECT id, services, CONCAT(date, ' ', time) AS start FROM schedule";
$result = $conn->query($query);

$events = array();

while ($row = $result->fetch_assoc()) {
    $start = new DateTime($row['start']);
    $end = clone $start;
    $end->modify('+1 hour');
    
    $events[] = array(
        'id' => $row['id'],
        'title' => $row['services'],
        'start' => $start->format('Y-m-d\TH:i:s'),
        'end' => $end->format('Y-m-d\TH:i:s'),
    );
}

echo json_encode($events);
?>

