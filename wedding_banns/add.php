<?php

include '../process/connect.php';

require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    extract($_POST);

    // Validate title and content
    // if (empty($title) || empty($content)) {
    //     echo ("<SCRIPT LANGUAGE='JavaScript'>
    //         window.alert('Title and content are required.')
    //         window.location.href='../wedding_banns.php';
    //         </SCRIPT>");
    //     exit;
    // }
    $status = 'ongoing';
    $reference_id = "WEDBANNS" . uniqid();

    Configuration::instance([
        'cloud' => [
          'cloud_name' => 'djpkvzlai', 
          'api_key' => '221129169276994', 
          'api_secret' => '5QO6KwczMhxmWt2OAGxLg2dCJcE'],
          'url' => [
          'secure' => true]]);

        $image_groom = $_FILES['id_picture_groom']['tmp_name'];
        $result_image_groom = (new UploadApi())->upload($image_groom);
        $groom_image_url = $result_image_groom['secure_url'];
        
        $image_bride = $_FILES['id_picture_bride']['tmp_name'];
        $result_image_bride= (new UploadApi())->upload($image_bride);
        $bride_image_url = $result_image_bride['secure_url'];

        
        $sql = "INSERT INTO `wedding_banns` (`date_marriage`, `place_marriage`, `id_picture_groom`, `id_picture_bride`, `groom_name`, `groom_age`, `groom_father_name`, `groom_mother_name`, `bride_name`, `bride_age`, `bride_father_name`, `bride_mother_name`, `status`, `reference_id`) VALUES (:date_marriage, :place_marriage, :groom_image_url, :bride_image_url, :groom_name, :groom_age, :groom_father_name, :groom_mother_name, :bride_name, :bride_age, :bride_father_name, :bride_mother_name, :status, :reference_id)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":date_marriage", htmlspecialchars($date_marriage));
    $stmt->bindValue(":place_marriage", htmlspecialchars($place_marriage));
    $stmt->bindValue(":groom_image_url", $groom_image_url);
    $stmt->bindValue(":bride_image_url", $bride_image_url);
    $stmt->bindValue(":groom_name", htmlspecialchars($groom_name));
    $stmt->bindValue(":groom_age", htmlspecialchars($groom_age));
    $stmt->bindValue(":groom_father_name", htmlspecialchars($groom_father_name));
    $stmt->bindValue(":groom_mother_name", htmlspecialchars($groom_mother_name));
    $stmt->bindValue(":bride_name", htmlspecialchars($bride_name));
    $stmt->bindValue(":bride_age", htmlspecialchars($bride_age));
    $stmt->bindValue(":bride_father_name", htmlspecialchars($bride_father_name));
    $stmt->bindValue(":bride_mother_name", htmlspecialchars($bride_mother_name));
    $stmt->bindValue(":status", $status);
    $stmt->bindValue(":reference_id", $reference_id);

    $result = $stmt->execute();

    if ($result) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Wedding banns added successfully!')
            window.location.href='../wedding_banns.php';
            </SCRIPT>");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
    }


?>