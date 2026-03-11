<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');

if (empty($email)) {
    http_response_code(400);
    echo json_encode([
        "message" => "Email is required"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "message" => "Invalid email format"
    ]);
    exit;
}

try {
    // This finds the user by email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Makes sure to always return a general message if user is not found
    if (!$user) {
        echo json_encode([
            "message" => "If that email exists, a reset link has been generated."
        ]);
        exit;
    }

    $userId = (int)$user['id'];

    // Delete any old reset tokens for this user
    $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $deleteStmt->execute([$userId]);

    // Generate a secure random token
    $token = bin2hex(random_bytes(32));

    // Store only the hashed version in the database
    $tokenHash = hash('sha256', $token);

    // The token expires in 1 hour
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // To save the reset request
    $insertStmt = $conn->prepare("
    INSERT INTO password_resets (user_id, token_hash, expires_at)
    VALUES (?, ?, ?)
");
$insertStmt->execute([$userId, $tokenHash, $expiresAt]);

    // To build the reset link
    $resetLink = "http://localhost:8080/pages/reset-password.html?token=" . $token;

    echo json_encode([
        "message" => "If that email exists, a reset link has been generated.",
        "reset_link" => $resetLink
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "message" => "Server error"
    ]);
}
?>