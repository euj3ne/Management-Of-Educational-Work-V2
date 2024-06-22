<?php
session_start();
include '../../../config/config.php';

try {
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'teacher')) {
        header('Location: ../content/login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_groups'])) {
        $groupsId = $_POST['groups_id'];
        $numberGroup = $_POST['number_group'];
        $numberStudents = $_POST['number_students'];
        $enrollmentYear = $_POST['enrollment_year'];
        $graduationYear = $_POST['graduation_year'];
        $classroomTeacher = $_POST['classroom_teacher'];

        
        $query = "UPDATE groups SET number_group = :number_group, number_students = :number_students, enrollment_year = :enrollment_year, graduation_year = :graduation_year, classroom_teacher = :classroom_teacher";
        $query .= " WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':number_group', $numberGroup, PDO::PARAM_STR);
        $stmt->bindParam(':number_students', $numberStudents, PDO::PARAM_STR);
        $stmt->bindParam(':enrollment_year', $enrollmentYear, PDO::PARAM_INT);
        $stmt->bindParam(':graduation_year', $graduationYear, PDO::PARAM_STR);
        $stmt->bindParam(':classroom_teacher', $classroomTeacher, PDO::PARAM_STR);
        $stmt->bindParam(':id', $groupsId, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ../../pages/groups.php');
        exit();
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
