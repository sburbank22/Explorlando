<?php
header('Content-Type: application/json; charset=utf-8');

session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "loggedIn" => false,
        "message" => "Not logged in"
    ]);
    exit;
}

$userId = (int) $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT id, email, name, username, bio, pronouns, interests, phone, date_of_birth, profile_picture
        FROM users
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode([
            "loggedIn" => false,
            "message" => "User not found"
        ]);
        exit;
    }

    echo json_encode([
        "loggedIn" => true,
        "user" => $user
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "loggedIn" => false,
        "message" => "Server error",
        "error" => $e->getMessage()
    ]);
}