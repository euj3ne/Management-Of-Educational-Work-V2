<?php
include 'config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL
)";
    $conn->exec($sql);
    echo "Таблица users создана успешно.<br>";

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $admin_password = password_hash('admin', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role) VALUES ('admin', '$admin_password', 'admin')";
        $conn->exec($sql);
        echo "Администратор добавлен успешно.<br>";
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = 'teacher'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $teacher_password = password_hash('teacher', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role) VALUES ('teacher', '$teacher_password', 'teacher')";
        $conn->exec($sql);
        echo "Преподаватель добавлен успешно.<br>";
    }

    $sql = "CREATE TABLE IF NOT EXISTS students (
        id SERIAL PRIMARY KEY ,
        last_name TEXT NOT NULL,
        first_name TEXT NOT NULL,
        middle_name TEXT NOT NULL,
        birth_date TEXT NOT NULL,
        gender TEXT NOT NULL,
        address TEXT NOT NULL,
        phone TEXT NOT NULL,
        email TEXT NOT NULL,
        group_number TEXT NOT NULL,
        enrollment_date TEXT NOT NULL,
        graduation_date TEXT NOT NULL
    )";
    $conn->exec($sql);
    echo "Таблица students создана успешно.<br>";

    $sql = "CREATE TABLE IF NOT EXISTS teachers (
        id SERIAL PRIMARY KEY ,
        last_name TEXT NOT NULL,
        first_name TEXT NOT NULL,
        middle_name TEXT NOT NULL,
        birth_date TEXT NOT NULL,
        gender TEXT NOT NULL,
        address TEXT NOT NULL,
        phone TEXT NOT NULL,
        email TEXT NOT NULL,
        employment_date TEXT NOT NULL,
        position TEXT NOT NULL
    )";
    $conn->exec($sql);
    echo "Таблица teachers создана успешно.<br>";

    $sql = "CREATE TABLE IF NOT EXISTS groups (
        id SERIAL PRIMARY KEY ,
        number_group TEXT NOT NULL,
        number_students TEXT NOT NULL,
        enrollment_year TEXT NOT NULL,
        graduation_year TEXT NOT NULL,
        classroom_teacher TEXT NOT NULL
    )";
    $conn->exec($sql);
    echo "Таблица groups создана успешно.<br>";

    $sql = "CREATE TABLE IF NOT EXISTS uploads (
        id SERIAL PRIMARY KEY,
        file_name TEXT NOT NULL,
        file_description TEXT NOT NULL,
        file_path TEXT NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        theme TEXT
    )";
    $conn->exec($sql);
    echo "Таблица uploads создана успешно.<br>";

    $sql = "CREATE TABLE IF NOT EXISTS documents (
        id SERIAL PRIMARY KEY,
        file_name TEXT NOT NULL,
        file_description TEXT NOT NULL,
        theme TEXT,
        file_path TEXT NOT NULL
    )";
    $conn->exec($sql);
    echo "Таблица documents создана успешно.<br>";

    $sql = "CREATE TABLE IF NOT EXISTS news (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    full_description TEXT NOT NULL,
    image TEXT
    )";
    $conn->exec($sql);
    echo "Таблица news создана успешно.<br>";

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

$conn = null;
?>