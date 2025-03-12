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

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance'])) {
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_id => $status) {
        try {
            $sql = "INSERT INTO attendance (student_id, date, status) VALUES (:student_id, :date, :status)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'student_id' => $student_id,
                'date' => $date,
                'status' => $status
            ]);
        } catch (PDOException $e) {
            die("Error recording attendance: " . $e->getMessage());
        }
    }

    echo "Attendance recorded successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Attendance</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Record Attendance</h1>
        <form method="POST" action="">
            <label for="class_id">Select Class:</label>
            <select name="class_id" required>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="date">Date:</label>
            <input type="date" name="date" required>
            <button type="submit">Fetch Students</button>
        </form>

        <?php if (!empty($students)): ?>
            <form method="POST" action="">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="date" value="<?= $date ?>">
                <table>
                    <tr>
                        <th>Student Name</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td>
                                <select name="attendance[<?= $student['id'] ?>]">
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <button type="submit">Submit Attendance</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>