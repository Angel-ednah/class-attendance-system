<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch attendance and performance records
try {
    // Fetch attendance
    $sql_attendance = "SELECT a.date, a.status, s.name, s.roll_number 
                       FROM attendance a 
                       JOIN students s ON a.student_id = s.id";
    $stmt_attendance = $pdo->prepare($sql_attendance);
    $stmt_attendance->execute();
    $attendance = $stmt_attendance->fetchAll(PDO::FETCH_ASSOC);

    // Fetch performance
    $sql_performance = "SELECT p.subject, p.marks, p.remarks, s.name, s.roll_number 
                        FROM performance p 
                        JOIN students s ON p.student_id = s.id";
    $stmt_performance = $pdo->prepare($sql_performance);
    $stmt_performance->execute();
    $performance = $stmt_performance->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}

// Handle report generation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $format = $_POST['format'];

    if ($format === 'excel') {
        generateExcelReport($attendance, $performance);
    } elseif ($format === 'pdf') {
        generatePdfReport($attendance, $performance);
    }
}

// Function to generate Excel report
function generateExcelReport($attendance, $performance) {
    require 'vendor/autoload.php'; // Include PhpSpreadsheet

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add attendance data
    $sheet->setCellValue('A1', 'Attendance Report');
    $sheet->setCellValue('A2', 'Date');
    $sheet->setCellValue('B2', 'Student Name');
    $sheet->setCellValue('C2', 'Roll Number');
    $sheet->setCellValue('D2', 'Status');

    $row = 3;
    foreach ($attendance as $record) {
        $sheet->setCellValue('A' . $row, $record['date']);
        $sheet->setCellValue('B' . $row, $record['name']);
        $sheet->setCellValue('C' . $row, $record['roll_number']);
        $sheet->setCellValue('D' . $row, $record['status']);
        $row++;
    }

    // Add performance data
    $sheet->setCellValue('F1', 'Performance Report');
    $sheet->setCellValue('F2', 'Student Name');
    $sheet->setCellValue('G2', 'Roll Number');
    $sheet->setCellValue('H2', 'Subject');
    $sheet->setCellValue('I2', 'Marks');
    $sheet->setCellValue('J2', 'Remarks');

    $row = 3;
    foreach ($performance as $record) {
        $sheet->setCellValue('F' . $row, $record['name']);
        $sheet->setCellValue('G' . $row, $record['roll_number']);
        $sheet->setCellValue('H' . $row, $record['subject']);
        $sheet->setCellValue('I' . $row, $record['marks']);
        $sheet->setCellValue('J' . $row, $record['remarks']);
        $row++;
    }

    // Save the file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="report.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}

// Function to generate PDF report
function generatePdfReport($attendance, $performance) {
    require('fpdf/fpdf.php'); // Include FPDF

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Add attendance data
    $pdf->Cell(40, 10, 'Attendance Report');
    $pdf->Ln();
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

    // Add performance data
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Performance Report');
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, 'Student Name', 1);
    $pdf->Cell(40, 10, 'Roll Number', 1);
    $pdf->Cell(40, 10, 'Subject', 1);
    $pdf->Cell(30, 10, 'Marks', 1);
    $pdf->Cell(40, 10, 'Remarks', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($performance as $record) {
        $pdf->Cell(50, 10, $record['name'], 1);
        $pdf->Cell(40, 10, $record['roll_number'], 1);
        $pdf->Cell(40, 10, $record['subject'], 1);
        $pdf->Cell(30, 10, $record['marks'], 1);
        $pdf->Cell(40, 10, $record['remarks'], 1);
        $pdf->Ln();
    }

    // Output the PDF
    $pdf->Output('D', 'report.pdf');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Generate Report</h1>
        <form method="POST" action="">
            <label for="format">Select Format:</label>
            <select name="format" required>
                <option value="excel">Excel</option>
                <option value="pdf">PDF</option>
            </select>
            <button type="submit">Generate Report</button>
        </form>
    </div>
</body>
</html>