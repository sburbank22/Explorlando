<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/requireAuth.php';
require_once __DIR__ . '/../db.php';

/* this get's the user's current profile pic */
$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->execute([$userId]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

$oldPicture = $currentUser['profile_picture'] ?? null;

if (!isset($_FILES['profile_picture'])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "No file uploaded."
    ]);
    exit;
}

$file = $_FILES['profile_picture'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Upload failed."
    ]);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Image must be under 5MB."
    ]);
    exit;
}

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!isset($allowedTypes[$mimeType])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Only JPG, PNG, GIF, and WEBP files are allowed."
    ]);
    exit;
}

$extension = $allowedTypes[$mimeType];
$fileName = 'user_' . $userId . '_' . time() . '.' . $extension;

$uploadDir = __DIR__ . '/../../uploads/profile_pictures/';
$relativePath = '/uploads/profile_pictures/' . $fileName;
$destination = $uploadDir . $fileName;

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Failed to save uploaded file."
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("
        UPDATE users
        SET profile_picture = ?
        WHERE id = ?
    ");
    $stmt->execute([$relativePath, $userId]);

    /* delete's the old profile picture if it exists */
if ($oldPicture) {
    $oldPath = __DIR__ . '/../../' . ltrim($oldPicture, '/');
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}
    echo json_encode([
        "success" => true,
        "message" => "Profile picture uploaded successfully.",
        "profile_picture" => $relativePath
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error."
    ]);
}