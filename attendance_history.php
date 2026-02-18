<?php
session_start();
include 'connect.php';

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Handle CSV export
if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=attendance_history.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student ID', 'Name', 'Status', 'Recorded At']);

    $query = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS name, status, recorded_at FROM attendance_history ORDER BY recorded_at DESC";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Apply filters
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM attendance_history WHERE 1=1";

if (!empty($filter_date)) {
    $query .= " AND DATE(recorded_at) = '$filter_date'";
}
if (!empty($search)) {
    $query .= " AND (student_id LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%')";
}

$query .= " ORDER BY recorded_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance History</title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .container {
            background: #fff;
            width: 90%;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            color: #009688;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input, button {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #009688;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background: #00796b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #009688;
            color: white;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background: #555;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📜 Attendance History</h2>

    <form method="GET">
        <input type="date" name="filter_date" value="<?= $filter_date ?>">
        <input type="text" name="search" placeholder="Search by ID or name" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Filter</button>
        <a href="attendance_history.php"><button type="button">Clear</button></a>
    </form>

    <form method="POST" style="text-align:center; margin-bottom:15px;">
        <button type="submit" name="export_csv">⬇️ Export to CSV</button>
    </form>

    <table>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Recorded At</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td style="color: <?= $row['status'] == 'Present' ? 'green' : 'red' ?>; font-weight:bold;">
                        <?= htmlspecialchars($row['status']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['recorded_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No attendance history found.</td></tr>
        <?php endif; ?>
    </table>

    <div style="text-align:center;">




        

<a href="<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') ? 't_homepage.php' : 'homepage.php'; ?>">
    Back to Main Page
</a>



    </div>
</div>

</body>
</html>
