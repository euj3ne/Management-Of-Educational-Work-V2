<?php
session_start();
include 'E:\OSPanel\home\eduworknew.local\config\config.php';

require 'E:\OSPanel\home\eduworknew.local\libs\PhpSpreadsheet\vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Номер группы');
$sheet->setCellValue('B1', 'Количество студентов');
$sheet->setCellValue('C1', 'Год поступления');
$sheet->setCellValue('D1', 'Год окончания');
$sheet->setCellValue('E1', 'Классный руководитель');

$result = $conn->query("SELECT number_group, number_students, enrollment_year, graduation_year, classroom_teacher FROM groups");

$row = 2;
while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A'.$row, $data['number_group']);
    $sheet->setCellValue('B'.$row, $data['number_students']);
    $sheet->setCellValue('C'.$row, $data['enrollment_year']);
    $sheet->setCellValue('D'.$row, $data['graduation_year']);
    $sheet->setCellValue('E'.$row, $data['classroom_teacher']);
    $row++;
}

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="groups.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
