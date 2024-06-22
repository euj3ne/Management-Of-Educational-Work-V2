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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_news'])) {
        if (!empty($_POST['news_id']) && is_numeric($_POST['news_id'])) {
            $newsId = intval($_POST['news_id']);
            $stmt = $conn->prepare("DELETE FROM news WHERE id = :id");
            $stmt->bindParam(':id', $newsId, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: ../../pages/news.php');
            exit();
        } else {
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
