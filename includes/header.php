
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_url = $_SERVER['REQUEST_URI'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>КТСиА | воспитательная работа</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100" style="background: rgb(255,255,255);border-style: none;">
    <header class="bg-dark" style="background: rgb(255,255,255);--bs-body-bg: rgb(255,255,255);">
        <nav class="navbar navbar-expand-md sticky-top py-3 navbar-dark" id="mainNav" style="background: rgb(99,90,211);">
            <div class="container"><a class="navbar-brand d-flex align-items-center" href="/"><img src="/../assets/img/logo.png" width="60" height="60"><span style="margin-left: 8px;color: rgb(255,255,255);">КТСиА</span></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#" style="font-weight: bold;color: #ffffff;border-color: rgba(255,255,255,0);">Списки</a>
                            <div class="dropdown-menu" style="background: rgb(66,70,133);">
                                <a class="dropdown-item" href="/../content/list_students.php" style="border-radius: 6px;border-color: rgba(255,255,255,0.24); color: #ffffff;">Студенты</a>
                                <a class="dropdown-item" href="/../content/list_teachers.php" style="border-radius: 6px;border-color: rgba(255,255,255,0.24); color: #ffffff;">Преподаватели</a>
                                <a class="dropdown-item" href="/../content/list_groups.php" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">Группы</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#" style="font-weight: bold;color: var(--bs-body-bg);">Документы</a>
                            <div class="dropdown-menu" style="background: rgb(66,70,133);">
                                <a class="dropdown-item" href="/../content/journal_of_educational_work.php" style="border-radius: 6px;border-style: none; color: #ffffff;">Журнал по воспитательной работе</a>
                                <a class="dropdown-item" href="/../content/thematic_planning.php" style="border-radius: 6px; color: #ffffff;">Тематическое планирование "Разговоры о важном"</a>
                                <a class="dropdown-item" href="/../content/pedagogical_characteristics.php" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">Педагогическая характеристика</a>
                                <a class="dropdown-item" href="/../content/social_passport.php" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">Социальный паспорт</a>
                                <a class="dropdown-item" href="/../content/group_activity.php" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">Активность групп</a>
                                <a class="dropdown-item" href="/../content/reminder_for_parents.php" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">Памятка для родителей</a>
                                <a class="dropdown-item" href="/../content/minutes_parent_meeting.php" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">Протокол родительского собрания</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#" style="font-weight: bold;color: var(--bs-body-bg);">Планы</a>
                            <div class="dropdown-menu" style="background: rgb(66,70,133);">
                                <a class="dropdown-item" href="../content/work_plan.php" style="border-radius: 6px;border-style: none; color: #ffffff;">План работы</a>
                                <a class="dropdown-item" href="../content/plan_for_training_camps.php" style="border-radius: 6px; color: #ffffff;">План на учебные сборы</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#" style="font-weight: bold;color: rgb(255,255,255);">Отчеты</a>
                            <div class="dropdown-menu" style="background: rgb(66,70,133);">
                                <a class="dropdown-item" href="../content/educational_work_report.php" style="border-radius: 6px;border-color: rgba(255,255,255,0.24); color: #ffffff;">Отчет воспитательной работы</a></div>
                        </li>
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#" style="font-weight: bold;color: rgb(255,255,255);">Архив</a>
                            <div class="dropdown-menu" style="background: rgb(66,70,133);"><a class="dropdown-item" href="#" style="border-color: rgba(255,255,255,0.24);border-radius: 6px; color: #ffffff;">2024</a></div>
                        </li>

                        <li class="nav-item"><a class="nav-link active" href="../content/events.php" style="font-weight: bold;color: rgb(255,255,255);">Мероприятия</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                    </ul><a class="btn btn-primary shadow" role="button" href="/admin/index.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Панель управления</a>
                        <?php endif; ?>
                    </ul><a class="btn btn-primary shadow" role="button" href="/auth/logout.php" style="background: rgb(66,70,133);border-color: #ffffff00; margin-left: 10px;">Деавторизироваться</a>
                    <?php else: ?>
                    </ul><a class="btn btn-primary shadow" role="button" href="/../auth/login.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Авторизация</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>