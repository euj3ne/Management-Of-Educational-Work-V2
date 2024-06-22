<?php

$db_host = "127.127.126.49";
$db_name = "edudb";
$db_user = "postgres";
$db_password = "postgres";

try {
    $conn = new PDO("pgsql:host=$db_host;dbname=$db_name;user=$db_user;password=$db_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}


?>