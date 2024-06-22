<?php
session_start();
include 'E:\OSPanel\home\eduworknew.local\config\config.php';

require 'E:\OSPanel\home\eduworknew.local\libs\PhpSpreadsheet\vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Фамилия');
$sheet->setCellValue('B1', 'Имя');
$sheet->setCellValue('C1', 'Отчество');
$sheet->setCellValue('D1', 'Дата рождения');
$sheet->setCellValue('E1', 'Пол');
$sheet->setCellValue('F1', 'Адрес');
$sheet->setCellValue('G1', 'Телефон');
$sheet->setCellValue('H1', 'Почта');
$sheet->setCellValue('I1', 'Номер группы');
$sheet->setCellValue('J1', 'Дата поступления');
$sheet->setCellValue('K1', 'Дата окончания');

$result = $conn->query("SELECT last_name, first_name, middle_name, birth_date, gender, address, phone, email, group_number, enrollment_date, graduation_date FROM students");

$row = 2;
while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A'.$row, $data['last_name']);
    $sheet->setCellValue('B'.$row, $data['first_name']);
    $sheet->setCellValue('C'.$row, $data['middle_name']);
    $sheet->setCellValue('D'.$row, $data['birth_date']);
    $sheet->setCellValue('E'.$row, $data['gender']);
    $sheet->setCellValue('F'.$row, $data['address']);
    $sheet->setCellValue('G'.$row, $data['phone']);
    $sheet->setCellValue('H'.$row, $data['email']);
    $sheet->setCellValue('I'.$row, $data['group_number']);
    $sheet->setCellValue('J'.$row, $data['enrollment_date']);
    $sheet->setCellValue('K'.$row, $data['graduation_date']);
    $row++;
}

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="students.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
