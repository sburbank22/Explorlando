<?php

$host     = getenv('MYSQLHOST')     ?: "db";
$dbname   = getenv('MYSQLDATABASE') ?: "explorlando";
$username = getenv('MYSQLUSER')     ?: "explorlando_user";
$password = getenv('MYSQLPASSWORD') ?: "password";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}