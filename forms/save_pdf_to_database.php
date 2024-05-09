<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icp_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get PDF data from POST request
$pdfData = isset($_POST['pdfData']) ? $_POST['pdfData'] : '';

// Decode Base64 PDF data
$pdfContent = base64_decode($pdfData);

// Prepare and execute SQL statement to insert PDF data into database
$sql = "INSERT INTO pdf_table (pdf_data) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("b", $pdfContent); // 'b' indicates a blob parameter
$stmt->execute();

// Check if PDF data was successfully inserted
if ($stmt->affected_rows > 0) {
    echo "PDF saved to database successfully.";
} else {
    echo "Failed to save PDF to database.";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
