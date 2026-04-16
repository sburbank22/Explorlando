<?php
header('Content-Type: application/json; charset=utf-8');

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed. Use POST."]);
    exit;
}

require_once __DIR__ . '/../db.php';

$input = json_decode(file_get_contents("php://input"), true);

$login = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if ($login === '' || $password === '') {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Username/email and password are required."]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT id, name, username, email, password_hash
        FROM users
        WHERE email = ? OR username = ?
        LIMIT 1
    ");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid username/email or password."]);
        exit;
    }

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['username'] = $user['username'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "data" => [
            "id" => (int)$user['id'],
            "name" => $user['name'],
            "username" => $user['username'],
            "email" => $user['email']
        ]
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server error."]);
}