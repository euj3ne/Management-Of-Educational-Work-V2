<?php

session_start();
include '../../../config/config.php';

$theme = $_GET['docs'] ?? "";
$stmt = $conn->prepare("SELECT * FROM groups");
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'teacher')) {
        header('Location: ../content/login.php');
        exit();
    }

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_groups'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $numberGroup = $_POST['number_group'];
        $numberStudents = $_POST['number_students'];
        $enrollmentYear = $_POST['enrollment_year'];
        $graduationYear = $_POST['graduation_year'];
        $classroomTeacher = $_POST['classroom_teacher'];
    
        try {
            $stmt = $conn->prepare("INSERT INTO groups (number_group, number_students, enrollment_year, graduation_year, classroom_teacher) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$numberGroup, $numberStudents, $enrollmentYear, $graduationYear, $classroomTeacher]);
            header('Location: ../../pages/groups.php');
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