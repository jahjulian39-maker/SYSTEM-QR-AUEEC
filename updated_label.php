<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo "Error: User not logged in.";
    exit;
}

if (isset($_POST['image_id']) && isset($_POST['label'])) {
    $userId = $_SESSION['id']; 
    $imageId = $_POST['image_id'];
    $label = $conn->real_escape_string($_POST['label']);

    // Debugging: Print received data
    echo "Received Data: image_id = $imageId, label = $label <br>";

    // Ensure the row exists before updating
    $checkSql = "SELECT * FROM marks WHERE user_id = '$userId' AND image_id = '$imageId'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update existing label
        $sql = "UPDATE marks SET label='$label' WHERE user_id='$userId' AND image_id='$imageId'";
    } else {
        // Insert new row if it doesn't exist
        $sql = "INSERT INTO marks (user_id, image_id, label, checked) VALUES ('$userId', '$imageId', '$label', 0)";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Success: Label saved!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error: Missing image_id or label.";
}
?>


