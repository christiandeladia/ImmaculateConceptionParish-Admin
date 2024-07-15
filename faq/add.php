<?php

include '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    extract($_POST);

    // Validate title and content
    if (empty($title) || empty($content)) {
        echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Title and content are required.')
            window.location.href='../faq.php';
            </SCRIPT>");
        exit;
    }
    $status = 'active';
    // Insert the FAQ into the database
    $sql = "INSERT INTO `faq` (`title`, `content`, `status`) VALUES (:title, :content, :status)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":title", htmlspecialchars($title));
        $stmt->bindValue(":content", htmlspecialchars($content));
        $stmt->bindValue(":status", $status); // Assuming $status is set elsewhere in your code

        $result = $stmt->execute();

        if ($result) {
            echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('FAQ added successfully!')
                window.location.href='../faq.php';
                </SCRIPT>");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>
