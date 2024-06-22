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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_students'])) {
        $studentsId = $_POST['students_id'];
        $lastName = $_POST['last_name'];
        $firstName = $_POST['first_name'];
        $middleName = $_POST['middle_name'];
        $birthDate = $_POST['birth_date'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $groupNumber = $_POST['group_number'];
        $enrollmentDate	 = $_POST['enrollment_date'];
        $graduationDate	 = $_POST['graduation_date'];
        
        $query = "UPDATE students SET last_name = :last_name, first_name = :first_name, middle_name = :middle_name, birth_date = :birth_date, gender = :gender, address = :address, phone = :phone, email = :email, group_number = :group_number, enrollment_date = :enrollment_date, graduation_date = :graduation_date";
        $query .= " WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':middle_name', $middleName, PDO::PARAM_STR);
        $stmt->bindParam(':birth_date', $birthDate, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_INT);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':group_number', $groupNumber, PDO::PARAM_INT);
        $stmt->bindParam(':enrollment_date', $enrollmentDate, PDO::PARAM_STR);
        $stmt->bindParam(':graduation_date', $graduationDate, PDO::PARAM_STR);
        $stmt->bindParam(':id', $studentsId, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ../../pages/students.php');
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
