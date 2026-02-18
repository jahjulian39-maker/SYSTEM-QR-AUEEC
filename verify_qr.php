<?php
include("connect.php");

// If scanned via fetch (verification)
if (isset($_GET['qrcode'])) {
    $qr = $_GET['qrcode'];
    $result = $conn->query("SELECT id, firstName, lastName FROM users WHERE id='$qr'");

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'id' => $row['id'],
            'name' => $row['firstName'] . ' ' . $row['lastName']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

// If verified manually (button click)
if (isset($_POST['verify'])) {
    $id = $_POST['student_id'];

    // Mark present in masterlist
    $conn->query("UPDATE masterlist SET status='Present' WHERE student_id='$id'");

    header("Location: qr_scan.php?success=1");
    exit();
}
?>
