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
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_documents'])) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $file_name = $_POST['name'];
        $file_description = $_POST['description'];
        $theme = $_POST['theme'];
    
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "../../../uploads/documents/";
            $targetFile = $targetDir . basename($_FILES['file']['name']);
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $file_path = $targetFile;
                
                $stmt = $conn->prepare("INSERT INTO documents (file_name, file_description, theme, file_path) VALUES (?, ?, ?, ?)");
                $stmt->execute([$file_name, $file_description, $theme, $file_path]);
                
                header('Location: ../../pages/documents.php');
                exit();
            } else {
                $errorMessage = "Ошибка при перемещении файла в целевую директорию.";
                echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($errorMessage) . "');</script>";
                echo "Ошибка при выполнении программы: " . htmlspecialchars($errorMessage);
            }
        } else {
            $errorMessage = "Произошла ошибка при загрузке файла.";
            echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($errorMessage) . "');</script>";
            echo "Ошибка при выполнении программы: " . htmlspecialchars($errorMessage);
        }
    }
    
} catch (PDOException $e) {
    $errorMessage = "Database Error: " . $e->getMessage();
    echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($errorMessage) . "');</script>";
    echo "Ошибка при выполнении программы: " . htmlspecialchars($errorMessage);
    exit();
} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
    echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($errorMessage) . "');</script>";
    echo "Ошибка при выполнении программы: " . htmlspecialchars($errorMessage);
    exit();
}
?>