<?php
session_start();
define('ROOT_DIR', dirname(__DIR__));
include ROOT_DIR . '/config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../index.php');
        }
        exit();
    } else {
        $error = "Неверное имя пользователя или пароль.";
    }
}
?>

<?php include '../includes/header.php'; ?>
    <section class="py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body text-center d-flex flex-column align-items-center" style="background: #635ad3;border-color: rgba(255,255,255,0);border-radius: 14px;padding-top: 32px;padding-bottom: 20px;">
                            <h1 style="margin-bottom: 30px;">Авторизация</h1>
                            <form method="post">
                                <div class="mb-3">
                                    <p class="text-start" style="margin-bottom: 0px;">Логин или почта</p><input class="form-control" type="text" name="username" placeholder="work@euj3ne.ru">
                                </div>
                                <div class="mb-3">
                                    <p class="text-start" style="margin-bottom: 0px;">Пароль</p><input class="form-control" type="password" name="password" placeholder="********">
                                </div>
                                <div class="mb-3"><button class="btn btn-primary shadow d-block w-100" type="submit" style="background: rgb(66,70,133);">Авторизироваться</button>
                                <?php if (!empty($error)): ?>
                                    <p class="text-center" style="margin-bottom: 0px;margin-top: 28px;"><?php echo $error; ?></p>
                                <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include '../includes/footer.php'; ?>


