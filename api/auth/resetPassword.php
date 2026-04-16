<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents("php://input"), true);

$token = trim($data['token'] ?? '');
$newPassword = trim($data['new_password'] ?? '');

if (empty($token) || empty($newPassword)) {
    http_response_code(400);
    echo json_encode([
        "message" => "Token and new password are required"
    ]);
    exit;
}

if (strlen($newPassword) < 8) {
    http_response_code(400);
    echo json_encode([
        "message" => "Password must be at least 8 characters"
    ]);
    exit;
}

try {

    // This hashes the incoming token
    $tokenHash = hash('sha256', $token);

    // Find matching reset record
    $stmt = $conn->prepare("
    SELECT id, user_id
    FROM password_resets
    WHERE token_hash = ?
    AND used_at IS NULL
    AND expires_at > NOW()
    LIMIT 1
");
$stmt->execute([$tokenHash]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
        http_response_code(400);
        echo json_encode([
            "message" => "Invalid or expired reset token"
        ]);
        exit;
    }

    $resetId = $reset['id'];
    $userId = $reset['user_id'];

    // This hashes the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Updates the  user's password
    $updateStmt = $conn->prepare("
    UPDATE users
    SET password_hash = ?
    WHERE id = ?
");
$updateStmt->execute([$hashedPassword, $userId]);
    // Marks the reset token as used
    $usedStmt = $conn->prepare("
    UPDATE password_resets
    SET used_at = NOW()
    WHERE id = ?
");
$usedStmt->execute([$resetId]);

    echo json_encode([
        "message" => "Password reset successful"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "message" => "Server error"
    ]);
}
?>