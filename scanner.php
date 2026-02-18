<?php
session_start();
include 'connect.php';


if (!isset($_SESSION['id'])) {
    header("Location: index.php?error=Please%20log%20in%20first!");
    exit();
}

if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php?error=Access%20denied!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QR Attendance Scanner</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <style>
    body {
      font-family: Arial;
      text-align: center;
      background: #f5f5f5;
    }
    h2 {
      color: #009688;
      margin-top: 20px;
    }
    #reader {
      width: 400px;
      margin: 40px auto;
      border: 3px solid #009688;
      border-radius: 10px;
      background: #fff;
    }
    #result-box {
      display: none;
      margin-top: 20px;
      padding: 20px;
      border-radius: 10px;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      width: 400px;
      margin-left: auto;
      margin-right: auto;
    }
    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #009688;
      margin-bottom: 10px;
    }
    button {
      background: #009688;
      color: white;
      border: none;
      padding: 10px 20px;
      margin: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    button:hover {
      background: #00796b;
    }
    .back-btn {
      display: inline-block;
      margin-top: 30px;
      background: #555;
    }
    .back-btn:hover {
      background: #333;
    }
  </style>
</head>
<body>

  <h2>QR Attendance Scanner</h2>
  <div id="reader"></div>

  <div id="result-box">
    <img id="studentPhoto" class="profile-pic" src="default.png" alt="Student Photo">
    <h3>Student Information</h3>
    <p><strong>ID:</strong> <span id="studentId"></span></p>
    <p><strong>Name:</strong> <span id="studentName"></span></p>
    <p><strong>Status:</strong> <span id="studentStatus"></span></p>

    <form id="verifyForm">
      <input type="hidden" name="student_id" id="hiddenStudentId">
      <button type="button" id="verifyBtn">Verify (Mark Present)</button>
      <button type="button" id="cancelBtn">Cancel</button>
    </form>
  </div>

  <a href="t_homepage.php"><button class="back-btn">← Back to Homepage</button></a>

  <script>
    const reader = new Html5Qrcode("reader");
    const resultBox = document.getElementById("result-box");
    const studentPhoto = document.getElementById("studentPhoto");
    const studentIdEl = document.getElementById("studentId");
    const studentNameEl = document.getElementById("studentName");
    const studentStatusEl = document.getElementById("studentStatus");
    const hiddenStudentId = document.getElementById("hiddenStudentId");

    function startScanner() {
      reader.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        async (decodedText) => {
          await reader.stop();

         // ✅ Allow alphanumeric student IDs like STU-XXXXXXX
if (!/^STU-[A-Z0-9]+$/i.test(decodedText.trim())) {
  alert("⚠️ Invalid QR code format! Must be a valid student ID.");
  startScanner();
  return;


          }

          fetch("verify_scan.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "student_id=" + encodeURIComponent(decodedText)
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              studentIdEl.textContent = data.student_id;
              studentNameEl.textContent = data.name;
              studentStatusEl.textContent = data.status || "Unknown";
              studentPhoto.src = data.profile_picture || "default.png";
              hiddenStudentId.value = data.student_id;
              resultBox.style.display = "block";
            } else {
              alert("⚠️ Student not found!");
              startScanner();
            }
          })
          .catch(err => {
            alert("Error verifying QR.");
            console.error(err);
            startScanner();
          });
        },
        (errorMessage) => {}
      );
    }

    document.getElementById("verifyBtn").addEventListener("click", () => {
      const sid = hiddenStudentId.value;
      fetch("mark_present.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "student_id=" + encodeURIComponent(sid)
      })
      .then(res => res.text())
      .then(msg => {
        alert(msg);
        resultBox.style.display = "none";
        startScanner();
      });
    });

    document.getElementById("cancelBtn").addEventListener("click", () => {
      resultBox.style.display = "none";
      startScanner();
    });

    startScanner();
  </script>

</body>
</html>
