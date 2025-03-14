<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: ../login.php');
    exit();
}

// Fetch classes
try {
    $sql = "SELECT * FROM classes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching classes: " . $e->getMessage());
}

// Fetch students for the selected class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];

    try {
        $sql = "SELECT * FROM students WHERE class = (SELECT class_name FROM classes WHERE id = :class_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['class_id' => $class_id]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching students: " . $e->getMessage());
    }
}

// Handle performance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['performance'])) {
    $class_id = $_POST['class_id'];
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];
    $remarks = $_POST['remarks'];

    try {
        $sql = "INSERT INTO performance (student_id, subject, marks, remarks) VALUES (:student_id, :subject, :marks, :remarks)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $student_id,
            'subject' => $subject,
            'marks' => $marks,
            'remarks' => $remarks
        ]);
        echo "Performance recorded successfully!";
    } catch (PDOException $e) {
        die("Error recording performance: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Performance</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
    <a href="dashboard.php" class="back-btn">Go home</a>
        <h1>Record Performance</h1>
        <form method="POST" action="">
            <label for="class_id">Select Class:</label>
            <select name="class_id" required>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Fetch Students</button>
        </form>

        <?php if (!empty($students)): ?>
            <form method="POST" action="">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <label for="student_id">Select Student:</label>
                <select name="student_id" required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="subject">Subject:</label>
                <input type="text" name="subject" placeholder="Enter subject" required>
                <label for="marks">Marks:</label>
                <input type="number" name="marks" placeholder="Enter marks" required>
                <label for="remarks">Remarks:</label>
                <textarea name="remarks" placeholder="Enter remarks"></textarea>
                <button type="submit" name="performance">Submit Performance</button>
            </form>
        <?php endif; ?>
    </div>
    <footer>
    &copy; 2025 - All right reserved - CAS&trade;
    </footer>
</body>
</html>