<?php
include 'connect.php';

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // ✅ Check if student exists in masterlist
    $check = $conn->query("SELECT * FROM masterlist WHERE student_id = '$student_id'");

    if ($check->num_rows > 0) {
        // ✅ Student exists — mark present and update time
        $conn->query("
            UPDATE masterlist 
            SET status = 'Present', scan_time = NOW() 
            WHERE student_id = '$student_id'
        ");

        echo "✅ Attendance marked as Present!";
    } else {
        // ✅ Student not in masterlist yet — insert automatically
        $user = $conn->query("SELECT firstName, lastName FROM users WHERE student_id = '$student_id'");
        if ($user && $user->num_rows > 0) {
            $info = $user->fetch_assoc();
            $first = $info['firstName'];
            $last = $info['lastName'];

            $conn->query("
                INSERT INTO masterlist (student_id, first_name, last_name, status, scan_time) 
                VALUES ('$student_id', '$first', '$last', 'Present', NOW())
            ");
            echo "✅ New student added and marked as Present!";
        } else {
            echo "⚠️ Student not found in users table!";
        }
    }
} else {
    echo "⚠️ No student ID received!";
}
?>
