<?php

include '../process/connect.php';

require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    extract($_POST);

    // Validate title and content
    if (empty($title) || empty($content)) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Title and content are required.')
            window.location.href='../blog.php';
            </SCRIPT>");
        exit;
    }
    $status = 'active';

    Configuration::instance([
        'cloud' => [
          'cloud_name' => 'djpkvzlai', 
          'api_key' => '221129169276994', 
          'api_secret' => '5QO6KwczMhxmWt2OAGxLg2dCJcE'],
          'url' => [
          'secure' => true]]);
        $image = $_FILES['image']['tmp_name'];
        $result_image = (new UploadApi())->upload($image);
        $image_url = $result_image['secure_url'];

    $sql = "INSERT INTO `blog` (`date`, `title`, `content`, `status`, `image`) VALUES (:date, :title, :content, :status, :image_url)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":date", htmlspecialchars($date));
        $stmt->bindValue(":title", htmlspecialchars($title));
        $stmt->bindValue(":content", htmlspecialchars($content));
        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":image_url", $image_url);

        $result = $stmt->execute();

        if ($result) {
            echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('Blog added successfully!')
                window.location.href='../blog.php';
                </SCRIPT>");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>
