<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, Teacher!</h1>
        <div class="dashboard-links">
    <a href="record_attendance.php">Record Attendance</a>
    <a href="record_performance.php">Record Performance</a>
    <a href="view_students.php">View Students</a>
    <a href="../generate_report.php">Generate Report</a>
    <a href="../logout.php">Logout</a>
</div>
    </div>
</body>
</html>