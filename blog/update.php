<?php
require_once '../process/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blogid = $_POST['blog_id'];
    $dateBlog = $_POST['date'];
    $titleBlog = $_POST['title'];
    $contentBlog = $_POST['content'];

    $status = "active"; 
    // Update the blog in the database
    $query = "UPDATE blog SET 
              date = ?,
              title = ?, 
              content = ?, 
              status = ?
              WHERE blog_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$dateBlog, $titleBlog, $contentBlog, $status, $blogid]);

    // Optionally, you can check if the update was successful and provide feedback to the user
    if ($statement->rowCount() > 0) {
        echo "blog updated successfully!";
    } else {
        echo "Failed to update blog.";
    }
}
?>
