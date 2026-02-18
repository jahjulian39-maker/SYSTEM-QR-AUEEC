<?php
session_start();
include 'connect.php';
include 'phpqrcode/qrlib.php'; // make sure phpqrcode folder exists!

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['id'];

// Fetch user details (to get student_id)
$userQuery = "SELECT * FROM users WHERE id = '$userId'";
$userResult = $conn->query($userQuery);
$user = $userResult->fetch_assoc();

// Save student_id to session for consistency
$studentId = $user['student_id'];
$_SESSION['student_id'] = $studentId;

// Fetch profile details using student_id
$profileQuery = "SELECT * FROM profile WHERE student_id = '$studentId'";
$profileResult = $conn->query($profileQuery);
$profile = $profileResult->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveProfile'])) {
    $status = $_POST['status'];

    // File upload handling
    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadDir = "uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            $profilePicture = $targetFile;
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        $profilePicture = $profile['profile_picture'] ?? null;
    }

    // Update or insert profile
    if ($profile) {
        $updateQuery = "UPDATE profile 
                        SET profile_picture = '$profilePicture', status = '$status' 
                        WHERE student_id = '$studentId'";
    } else {
        $updateQuery = "INSERT INTO profile (student_id, profile_picture, status) 
                        VALUES ('$studentId', '$profilePicture', '$status')";
    }

    if ($conn->query($updateQuery)) {
        header("Location: profile.php?success=Profile updated!");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

// ✅ Handle QR code generation using student_id
if (isset($_POST['generateQR'])) {
    $qrDir = "qrcodes/";
    if (!file_exists($qrDir)) {
        mkdir($qrDir, 0777, true);
    }

    $qrData = $studentId;

    if (empty($qrData)) {
        echo "<script>alert('Student ID not found. Please contact the admin.'); window.location.href='profile.php';</script>";
        exit();
    }

    $qrFile = $qrDir . $studentId . ".png";

    // Generate QR image
    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);

    // Save QR file path in users table
    $updateQR = "UPDATE users SET qr_code='$qrFile' WHERE student_id='$studentId'";
    $conn->query($updateQR);

    header("Location: profile.php?success=QR Code generated!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
       
        <div class="profile-picture">
            <img src="<?php echo $profile['profile_picture'] ?? 'default.png'; ?>" alt="Profile Picture" width="150">
        </div>
          <h1><?php echo $user['firstName'] . " " . $user['lastName']; ?>'s Profile</h1>

        <!-- ✅ QR SECTION -->
        <div class="qr-section">
           
            <p><strong>Student ID:</strong> <?php echo $studentId ?? 'Not assigned'; ?></p>

            <?php if (!empty($user['qr_code'])): ?>
                <img src="<?php echo $user['qr_code']; ?>" alt="Your QR Code" width="150"><br>
                <a href="<?php echo $user['qr_code']; ?>" download>Download QR Code</a>
            <?php else: ?>
                <p>No QR code found.</p>
                <form method="POST">
                    <button type="submit" name="generateQR">Generate QR Code</button>
                </form>
            <?php endif; ?>
        </div>
        <!-- ✅ END QR SECTION -->

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture"><br>

            <label for="status">Update Status:</label>
            <textarea name="status" id="status"><?php echo $profile['status'] ?? ''; ?></textarea><br>

            <button type="submit" name="saveProfile">Save Changes</button>
        </form>


<a href="<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') ? 't_homepage.php' : 'homepage.php'; ?>">
    Back to Main Page
</a>


    </div>
</body>
</html>
