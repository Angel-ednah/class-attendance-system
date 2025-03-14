<?php
session_start();
require './db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $sql_attendance = "SELECT a.date, a.status, s.name, s.roll_number 
                       FROM attendance a 
                       JOIN students s ON a.student_id = s.id";
    $stmt_attendance = $pdo->prepare($sql_attendance);
    $stmt_attendance->execute();
    $attendance = $stmt_attendance->fetchAll(PDO::FETCH_ASSOC);

    $sql_performance = "SELECT p.subject, p.marks, p.remarks, s.name, s.roll_number 
                        FROM performance p 
                        JOIN students s ON p.student_id = s.id";
    $stmt_performance = $pdo->prepare($sql_performance);
    $stmt_performance->execute();
    $performance = $stmt_performance->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['format'] === 'pdf') {
    generatePdfReport($attendance, $performance);
}
function generatePdfReport($attendance, $performance) {
     require 'vendor/setasign/fpdf/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();

    // Set page margins (left, top, right)
    $pdf->SetMargins(10, 10, 10); 

    // Title of the document
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 10, 'School Attendance & Performance Report', 0, 1, 'C');
    $pdf->Ln(10); // Add some space

    // Attendance Report
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Attendance Report', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Date', 1);
    $pdf->Cell(50, 10, 'Student Name', 1);
    $pdf->Cell(40, 10, 'Roll Number', 1);
    $pdf->Cell(30, 10, 'Status', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($attendance as $record) {
        $pdf->Cell(40, 10, $record['date'], 1);
        $pdf->Cell(50, 10, $record['name'], 1);
        $pdf->Cell(40, 10, $record['roll_number'], 1);
        $pdf->Cell(30, 10, $record['status'], 1);
        $pdf->Ln();
    }

    // Add space between sections
    $pdf->Ln(10);

    // Performance Report with resized table and bottom margin
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Performance Report', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 8, 'Student Name', 1);
    $pdf->Cell(30, 8, 'Roll Number', 1);
    $pdf->Cell(50, 8, 'Subject', 1);
    $pdf->Cell(20, 8, 'Marks', 1);
    $pdf->Cell(30, 8, 'Remarks', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($performance as $record) {
        $pdf->Cell(60, 8, $record['name'], 1);
        $pdf->Cell(30, 8, $record['roll_number'], 1);
        $pdf->Cell(50, 8, $record['subject'], 1);
        $pdf->Cell(20, 8, $record['marks'], 1);
        $pdf->Cell(30, 8, $record['remarks'], 1);
        $pdf->Ln();
    }

    // Add bottom margin (extra space)
    $pdf->Ln(20);

    // Force download the PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Attendance_Performance_Report.pdf"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    $pdf->Output('D', 'Attendance_Performance_Report.pdf');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
    <div class="container">
        <a href="./teacher/dashboard.php" class="back-btn">Go home</a>
        <h1>Generate Report</h1>
        <form method="POST" action="">
            <label for="format">Select Format:</label>
            <select name="format" required>
                <option value="pdf">PDF</option>
            </select>
            <button type="submit">Generate Report</button>
        </form>
    </div>
    <footer>
        &copy; 2025 - All right reserved - CAS&trade;
    </footer>
</body>
</html>
