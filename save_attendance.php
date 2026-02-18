<?php
include 'connect.php';

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Check if student exists
    $checkUser = $conn->query("SELECT * FROM users WHERE student_id='$student_id'");
    if ($checkUser->num_rows > 0) {
        // Check if already scanned today
        $today = date('Y-m-d');
        $checkAttendance = $conn->query("SELECT * FROM attendance WHERE student_id='$student_id' AND DATE(scan_time)='$today'");
        
        if ($checkAttendance->num_rows > 0) {
            echo "<span class='error'>Attendance already recorded today.</span>";
        } else {
            $conn->query("INSERT INTO attendance (student_id, status) VALUES ('$student_id', 'Present')");
            echo "<span class='success'>Attendance saved for student ID: $student_id ✅</span>";
        }
    } else {
        echo "<span class='error'>Unknown QR code! Student not found.</span>";
    }
}
?>
