<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, Student!</h1>
        <div class="dashboard-links">
            <a href="view_attendance.php">View Attendance</a>
            <a href="view_performance.php">View Performance</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>
</html>