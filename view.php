<?php
session_start();
include 'connect.php';

$userId = $_SESSION['id']; // Ensure user is logged in

$sql = "SELECT * FROM uploads WHERE user_id = '$userId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Activities</title>
    <link rel="stylesheet" href="view.css">
    <script src="view.js" defer></script>
</head>
<body>
    <h1>Your Uploaded Activities</h1>
    
    <div class="image-container">
        <?php
        while ($row = $result->fetch_assoc()) {
            $files = explode(",", $row['file_path']);
            foreach ($files as $file) {
                echo "
                    <div class='image-box' id='image-{$row['id']}'>
                        <img src='$file' alt='Uploaded Image'>
                        <button class='delete-btn' data-id='{$row['id']}' data-file='$file'>Delete</button>
                    </div>
                ";
            }
        }
        ?>
    </div>

    <a href="homepage.php">Back to Main Page</a>
</body>
</html>
