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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_news'])) {
        $newsId = $_POST['news_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $fullDescription = $_POST['full_description'];

        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "../../../uploads/";
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowedTypes)) {
                if ($_FILES['image']['size'] <= 12 * 1024 * 1024) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $imagePath = $targetFile;
                    } else {
                        throw new Exception("Failed to move uploaded file.");
                    }
                } else {
                    throw new Exception("File is too large. Maximum size allowed is 12 MB.");
                }
            } else {
                throw new Exception("Only JPG, JPEG, PNG and GIF files are allowed.");
            }
        }

        $query = "UPDATE news SET name = :name, description = :description, full_description = :full_description";
        if (!empty($imagePath)) {
            $query .= ", image = :image";
        }
        $query .= " WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':full_description', $fullDescription, PDO::PARAM_STR);
        $stmt->bindParam(':id', $newsId, PDO::PARAM_INT);
        if (!empty($imagePath)) {
            $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
        }

        $stmt->execute();

        header('Location: ../../pages/news.php');
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
