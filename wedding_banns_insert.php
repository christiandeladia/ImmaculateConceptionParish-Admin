<?php
require "process/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];

    $result = mysqli_query($conn, "SELECT * FROM wedding WHERE id = $itemId");

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Check if the data already exists in wedding_banns table
        $checkQuery = "SELECT COUNT(*) AS count FROM wedding_banns WHERE reference_id = '{$row['reference_id']}'";
        $checkResult = mysqli_query($conn, $checkQuery);
        $checkRow = mysqli_fetch_assoc($checkResult);
        
        if ($checkRow['count'] > 0) {
            echo 'exists'; // Signal that data already exists
        } else {
            $status = 'ongoing';
            $place_marriage = 'Immaculate Conception Parish Pandi';
            $insertQuery = "INSERT INTO wedding_banns (reference_id, id_picture_groom, id_picture_bride, groom_name, groom_age, groom_father_name, groom_mother_name, bride_name, bride_age, bride_father_name, bride_mother_name, status, date_marriage, place_marriage) 
            VALUES ('{$row['reference_id']}', '{$row['id_picture_groom']}', '{$row['id_picture_bride']}', '{$row['groom_name']}', '{$row['groom_age']}', '{$row['groom_father_name']}', '{$row['groom_mother_name']}', '{$row['bride_name']}', '{$row['bride_age']}', '{$row['bride_father_name']}', '{$row['bride_mother_name']}', '$status', '{$row['date']}', '$place_marriage')";
            
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
