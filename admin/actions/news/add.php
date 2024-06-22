<?php
session_start();
include '../../../config/config.php';

$theme = $_GET['docs'] ?? "";
$stmt = $conn->prepare("SELECT * FROM uploads");
$stmt->execute();
$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'teacher')) {
        header('Location: ../content/login.php');
        exit();
    }

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_news'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $name = $_POST['name'];
        $description = $_POST['description'];
        $full_description = $_POST['full_description'];
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
                        echo "Ошибка при загрузке изображения.";
                    }
                } else {
                    echo "Файл слишком большой. Максимальный размер: 12 MB.";
                }
            } else {
                echo "Недопустимый тип файла. Поддерживаются только JPG, JPEG, PNG и GIF.";
            }
        } else {
            echo "Произошла ошибка при загрузке файла: ";
            switch ($_FILES['image']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    echo "Файл слишком большой.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    echo "Файл загружен частично.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    echo "Файл не был загружен.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    echo "Отсутствует временная папка для загрузки.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    echo "Не удалось записать файл на диск.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    echo "PHP-расширение остановило загрузку файла.";
                    break;
                default:
                    echo "Неизвестная ошибка.";
                    break;
            }
        }
    
        if ($imagePath) {
            $stmt = $conn->prepare("INSERT INTO news (name, description, full_description, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $full_description, $imagePath]);
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