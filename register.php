<?php
session_start();
include 'connect.php';
include 'phpqrcode/qrlib.php'; // Make sure this path is correct

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // You can improve security later
    $role = $_POST['role'];

    // Check if email exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        header("Location: index.php?error=Email%20already%20exists!");
        exit();
    }

    // Generate unique Student ID (e.g., STU-4A9X8B2C)
    function generateStudentID($length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $id = 'STU-';
        for ($i = 0; $i < $length; $i++) {
            $id .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $id;
    }

    $studentID = generateStudentID();

    // Ensure Student ID is unique
    $checkID = "SELECT * FROM users WHERE student_id = '$studentID'";
    $checkResult = $conn->query($checkID);
    while ($checkResult->num_rows > 0) {
        $studentID = generateStudentID();
        $checkResult = $conn->query("SELECT * FROM users WHERE student_id = '$studentID'");
    }

    // Insert new user
    $insertQuery = "INSERT INTO users (student_id, firstName, lastName, email, password, role) 
                    VALUES ('$studentID', '$firstName', '$lastName', '$email', '$password', '$role')";

    if ($conn->query($insertQuery) === TRUE) {
        $last_id = $conn->insert_id;

        // Generate QR Code based on Student ID
        $qrData = $studentID;
        $qrFile = 'qrcodes/' . $studentID . '.png';
        QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);

        // Save QR path in database
        $conn->query("UPDATE users SET qr_code='$qrFile' WHERE id=$last_id");

        header("Location: index.php?success=Account%20created%20successfully!");
        exit();
    } else {
        header("Location: index.php?error=Registration%20failed!");
        exit();
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['firstName'] = $row['firstName'];
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['role'] = $row['role'];




        if ($row['role'] === 'teacher') {
    header("Location: t_homepage.php");
} else {
    header("Location: homepage.php");
}
exit();

    } else {
        header("Location: index.php?error=Incorrect%20email%20or%20password!");
        exit();
    }
}
?>
