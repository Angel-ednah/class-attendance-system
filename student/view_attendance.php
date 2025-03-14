<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

// Fetch attendance for the logged-in student
$student_id = $_SESSION['user_id'];

try {
    $sql = "SELECT * FROM attendance WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_id' => $student_id]);
    $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching attendance: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
    <a href="dashboard.php" class="back-btn">Go home</a>
        <h1>View Attendance</h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php if (!empty($attendance)): ?>
                <?php foreach ($attendance as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['date']) ?></td>
                        <td><?= htmlspecialchars($record['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No attendance records found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <footer>
    &copy; 2025 - All right reserved - CAS&trade;
    </footer>
</body>
</html>