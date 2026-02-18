<?php
session_start();
include("connect.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php?error=Please%20log%20in%20first!");
    exit();
}

if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php?error=Access%20denied!");
    exit();
}


// Upload current masterlist to attendance_history
if (isset($_POST['upload_history'])) {
    $query = "INSERT INTO attendance_history (student_id, first_name, last_name, status, recorded_at)
              SELECT student_id, first_name, last_name, status, NOW() FROM masterlist";
    if ($conn->query($query)) {
        echo "<script>alert('✅ Attendance successfully uploaded to history!');</script>";
    } else {
        echo "<script>alert('❌ Failed to upload attendance history.');</script>";
    }
}

// Start new attendance session (reset all to Absent)
if (isset($_POST['new_attendance'])) {
    $reset = "UPDATE masterlist SET status='Absent'";
    if ($conn->query($reset)) {
        echo "<script>alert('🆕 New attendance session started — all statuses reset to Absent.');</script>";
    }
}


// Add student to masterlist
if (isset($_POST['add_student'])) {
    $userId = $_POST['student_id'];

    $result = $conn->query("SELECT student_id, firstName, lastName FROM users WHERE id='$userId'");
    if ($result && $row = $result->fetch_assoc()) {
        $studentId = $row['student_id'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];

        $check = $conn->query("SELECT * FROM masterlist WHERE student_id='$studentId'");
        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO masterlist (student_id, first_name, last_name, status) 
                          VALUES ('$studentId', '$firstName', '$lastName', 'Absent')");
        }
    }
}

// Remove student
if (isset($_POST['remove_student'])) {
    $studentId = $_POST['student_id'];
    $conn->query("DELETE FROM masterlist WHERE student_id='$studentId'");
}

// Update status manually
if (isset($_POST['update_status'])) {
    $studentId = $_POST['student_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE masterlist SET status='$status', scan_time=NOW() WHERE student_id='$studentId'");
}

$users = $conn->query("SELECT id, student_id, firstName, lastName FROM users");
$masterlist = $conn->query("SELECT * FROM masterlist");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Masterlist</title>
    <link rel="stylesheet" href="homepage.css">
     <style>
        body {
            color: #333;
        }

        .container {
            background: #fff;
            width: 85%;
            margin: 100px auto;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #008c96;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #008c96;
            color: white;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #009688;
            color: white;
            font-weight: bold;
        }

        button:hover {
            background-color: #00796b;
        }

        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .status-present {
            color: green;
            font-weight: bold;
        }

        .status-absent {
            color: red;
            font-weight: bold;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #009688;
        }

        .back:hover {
            text-decoration: underline;
        }

        .navbar {
            width: 85%;
            margin: auto;
            padding: 20px 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .navbar ul {
            list-style: none;
        }

        .navbar ul li {
            display: inline-block;
            margin-left: 20px;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
        }

        .navbar ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Masterlist</h2>

<div style="text-align:center; margin-bottom:20px;">
    <form method="POST" style="display:inline;">
        <button type="submit" name="upload_history">📤 Upload to Attendance History</button>
    </form>

    <form method="POST" style="display:inline;">
        <button type="submit" name="new_attendance" style="background:#f39c12;">🆕 New Attendance</button>
    </form>

    <a href="attendance_history.php">
        <button type="button" style="background:#009688;">📜 View History</button>
    </a>
</div>


        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Time</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $masterlist->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                <td class="<?php echo ($row['status'] == 'Present') ? 'status-present' : 'status-absent'; ?>">
                    <?php echo $row['status']; ?>
                </td>
                <td><?php echo $row['scan_time'] ?? '-'; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                        <select name="status">
                            <option value="Present" <?php if ($row['status']=='Present') echo 'selected'; ?>>Present</option>
                            <option value="Absent" <?php if ($row['status']=='Absent') echo 'selected'; ?>>Absent</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>

                    <form method="POST" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                                <button type="submit" name="remove_student" style="background-color:red;">Remove</button>
                            </form>
                </td>
            </tr>
            <?php } ?>
        </table>

       
</form>


        <h3>Add Student from Registered Users</h3>
        <form method="POST">
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php while ($user = $users->fetch_assoc()) { ?>
                    <option value="<?php echo $user['id']; ?>">
                        <?php echo $user['student_id'] . " - " . $user['firstName'] . " " . $user['lastName']; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" name="add_student">Add Student</button>
        </form>
         <a class="back" href="t_homepage.php">← Back to Home</a>
    </div>
</body>
</html>
