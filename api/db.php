<?php

$host = "db";
$dbname = "explorlando";
$username = "explorlando_user";
$password = "password";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode([
        "error" => "Database connection failed",
        "message" => $e->getMessage()
    ]);
}

?>