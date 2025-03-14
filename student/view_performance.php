<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

// Fetch performance for the logged-in student
$student_id = $_SESSION['user_id'];

try {
    $sql = "SELECT * FROM performance WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_id' => $student_id]);
    $performance = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching performance: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Performance</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
    <a href="dashboard.php" class="back-btn">Go home</a>
        <h1>View Performance</h1>
        <table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Remarks</th>
            </tr>
            <?php if (!empty($performance)): ?>
                <?php foreach ($performance as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['subject']) ?></td>
                        <td><?= htmlspecialchars($record['marks']) ?></td>
                        <td><?= htmlspecialchars($record['remarks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No performance records found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <footer>
    &copy; 2025 - All right reserved - CAS&trade;
    </footer>
</body>
</html>