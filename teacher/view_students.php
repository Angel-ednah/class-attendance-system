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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>View Students</h1>
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
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Roll Number</th>
                    <th>Class</th>
                </tr>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['id']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['roll_number']) ?></td>
                        <td><?= htmlspecialchars($student['class']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>