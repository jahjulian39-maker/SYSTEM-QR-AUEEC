<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];

    $sql = "SELECT u.student_id, u.firstName, u.lastName, 
                   p.profile_picture, p.status
            FROM users u
            LEFT JOIN profile p ON u.student_id = p.student_id
            WHERE u.student_id = '$student_id'";
    
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'student_id' => $row['student_id'],
            'name' => $row['firstName'] . ' ' . $row['lastName'],
            'profile_picture' => $row['profile_picture'],
            'status' => $row['status']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
