<?php
session_start();
include("connect.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php?error=Please%20log%20in%20first!");
    exit();
}

if ($_SESSION['role'] !== 'student') {
    header("Location: index.php?error=Access%20denied!");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Attendance System</title>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>




    <div class="banner">
        <div class="navbar">
            <div class="nav-right">
                <ul>
                    <li><a href="user_list.php">USERS</a></li>
                    <li><a href="logout.php">LOGOUT</a></li>
                    
                </ul>
            </div>
        </div>

        <div class="container">
            <h1>Chief ID(CID): Digital Attendance</h1>
            <p>Hello Students!</p>
            <div class="button-group">
                <a href="profile.php"><button type="button"><span></span>Profile</button></a>
                <a href="attendance_history.php"><button type="button"><span></span>Attendance History</button></a>

                
            </div>
        </div>
    </div>
</body>
</html>
