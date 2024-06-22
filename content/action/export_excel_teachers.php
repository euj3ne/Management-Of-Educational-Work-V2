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
$sheet->setCellValue('I1', 'Дата трудоустройства');
$sheet->setCellValue('J1', 'Должность');

$result = $conn->query("SELECT last_name, first_name, middle_name, birth_date, gender, address, phone, email, employment_date, position FROM teachers");

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
    $sheet->setCellValue('I'.$row, $data['position']);
    $sheet->setCellValue('J'.$row, $data['enrollment_date']);
    $row++;
}

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="teachers.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
