<?php

session_start();
include '../../../config/config.php';

$theme = $_GET['docs'] ?? "";
$stmt = $conn->prepare("SELECT * FROM teachers");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'teacher')) {
        header('Location: ../content/login.php');
        exit();
    }

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_teachers'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $lastName = $_POST['last_name'];
        $firstName = $_POST['first_name'];
        $middleName = $_POST['middle_name'];
        $birthDate = $_POST['birth_date'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $employmentDate = $_POST['employment_date'];
        $position = $_POST['position'];
    
        try {
            $stmt = $conn->prepare("INSERT INTO teachers (last_name, first_name, middle_name, birth_date, gender, address, phone, email, employment_date, position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$lastName, $firstName, $middleName, $birthDate, $gender, $address, $phone, $email, $employmentDate, $position]);
            header('Location: ../../pages/students.php');
            exit();
        } catch(Throwable $ex) {
            echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($ex->getMessage()) . "');</script>";
            echo "Ошибка при выполнении программы: " . htmlspecialchars($ex->getMessage());
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>