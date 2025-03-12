<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];

    try {
        $sql = "INSERT INTO classes (class_name) VALUES (:class_name)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['class_name' => $class_name]);
        echo "Class added successfully!";
    } catch (PDOException $e) {
        die("Error adding class: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Class</h1>
        <form method="POST" action="">
            <label for="class_name">Class Name:</label>
            <input type="text" name="class_name" placeholder="Enter class name" required>
            <button type="submit">Add Class</button>
        </form>
        <?php if (isset($_POST['class_name'])): ?>
            <div class="message">Class added successfully!</div>
        <?php endif; ?>
    </div>
</body>
</html>