<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $class = $_POST['class'];

    try {
        $sql = "INSERT INTO students (name, roll_number, class) VALUES (:name, :roll_number, :class)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'roll_number' => $roll_number, 'class' => $class]);
        echo "Student added successfully!";
    } catch (PDOException $e) {
        die("Error adding student: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
    <a href="dashboard.php" class="back-btn">Go home</a>
        <h1>Add Student</h1>
        <form method="POST" action="">
            <label for="name">Student Name:</label>
            <input type="text" name="name" placeholder="Enter student name" required>

            <label for="roll_number">Roll Number:</label>
            <input type="text" name="roll_number" placeholder="Enter roll number" required>

            <label for="class">Class:</label>
            <select name="class" required>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= htmlspecialchars($class['class_name']) ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Add Student</button>
        </form>
        <?php if (isset($_POST['name'])): ?>
            <div class="message">Student added successfully!</div>
        <?php endif; ?>
    </div>
    <footer>
    &copy; 2025 - All right reserved - CAS&trade;
    </footer>
</body>
</html>