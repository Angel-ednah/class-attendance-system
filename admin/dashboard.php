<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
            <h1>Welcome, Admin!</h1>
        <div class="dashboard-links">
            <a href="add_student.php">Add Student</a>
            <a href="insert_class.php">Add Class</a>
            <a href="view_records.php">View Records</a>
            <a href="generate_report.php">Generate Report</a>
            <!-- <a href="../generate_report.php">Generate Report</a> -->
            <a href="../logout.php">Logout</a>
        </div>
    </div>
<footer>
    &copy; 2025 - All right reserved - CAS&trade;
</footer> 
</body>
</html>