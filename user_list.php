<?php
session_start();
include 'connect.php';

// ✅ Use student_id for relation
$sql = "SELECT u.student_id, u.firstName, u.lastName, p.profile_picture, p.status 
        FROM users u
        LEFT JOIN profile p ON u.student_id = p.student_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users List</title>
    <link rel="stylesheet" href="users_list.css">
    <script src="users_list.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Existing Users</h2>
        <input type="text" id="searchInput" placeholder="Search users...">
        
        <div class="users-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="user-box">
                    <img src="<?= $row['profile_picture'] ?: 'default.png' ?>" alt="Profile Picture">
                    <p><strong><?= $row['firstName'] . " " . $row['lastName'] ?></strong></p>
                    <p>Student ID: <span class="user-id"><?= $row['student_id'] ?></span></p>
                    <p>Status: <?= $row['status'] ?: 'No status' ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    <a href="<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') ? 't_homepage.php' : 'homepage.php'; ?>">
    Back to Main Page
</a>

</body>
</html>
