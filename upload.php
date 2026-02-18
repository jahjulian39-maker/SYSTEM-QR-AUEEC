<?php
session_start();
include 'connect.php';

// Ensure user is logged in
if (!isset($_SESSION['id'])) {  // Use 'id' instead of 'user_id'
    die("You must be logged in to upload files.");
}

$userId = $_SESSION['id'];  // Get 'id' from session
$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Loop through each uploaded file
foreach ($_FILES['uploadedFile']['name'] as $key => $fileName) {
    $fileTmp = $_FILES['uploadedFile']['tmp_name'][$key];
    $fileSize = $_FILES['uploadedFile']['size'][$key];
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'mp4'];
    if (!in_array(strtolower($fileType), $allowedTypes)) {
        echo "Error: Invalid file type ($fileName)";
        continue;
    }

    if ($fileSize > 5 * 1024 * 1024) {
        echo "Error: File too large ($fileName)";
        continue;
    }

    $filePath = $uploadDir . time() . "_" . $fileName;
    if (move_uploaded_file($fileTmp, $filePath)) {
        $sql = "INSERT INTO uploads (user_id, file_path) VALUES ('$userId', '$filePath')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error saving file: " . $conn->error;
        }
    } else {
        echo "Error uploading file ($fileName)";
    }
}

// Redirect after upload
header("Location: homepage.php?success=Files Uploaded!");
exit();
?>
