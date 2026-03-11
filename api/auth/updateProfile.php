<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/requireAuth.php';
require_once __DIR__ . '/../db.php';

$input = json_decode(file_get_contents("php://input"), true);

$name = isset($input['name']) ? trim($input['name']) : null;
$email = isset($input['email']) ? trim($input['email']) : null;
$username = isset($input['username']) ? trim($input['username']) : null;
$bio = isset($input['bio']) ? trim($input['bio']) : null;
$pronouns = isset($input['pronouns']) ? trim($input['pronouns']) : null;
$interests = isset($input['interests']) ? trim($input['interests']) : null;
$phone = isset($input['phone']) ? trim($input['phone']) : null;
$date_of_birth = isset($input['date_of_birth']) ? trim($input['date_of_birth']) : null;


if ($name && strlen($name) > 100) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Name must be under 100 characters."
    ]);
    exit;
}

if ($username !== null && strlen($username) < 3) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Username must be at least 3 characters."
    ]);
    exit;
}

if ($username !== null && strlen($username) > 30) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Username must be under 30 characters."
    ]);
    exit;
}

if ($phone && strlen($phone) > 20) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Phone number is too long."
    ]);
    exit;
}

if ($bio && strlen($bio) > 500) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Bio must be under 500 characters."
    ]);
    exit;
}

if (
    $name === null &&
    $email === null &&
    $username === null &&
    $bio === null &&
    $pronouns === null &&
    $interests === null &&
    $phone === null &&
    $date_of_birth === null
) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "No profile fields were provided."
    ]);
    exit;
}

if ($email !== null && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid email format."
    ]);
    exit;
}

if ($date_of_birth !== null && $date_of_birth !== '') {
    $d = DateTime::createFromFormat('Y-m-d', $date_of_birth);
    if (!$d || $d->format('Y-m-d') !== $date_of_birth) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "date_of_birth must be in YYYY-MM-DD format."
        ]);
        exit;
    }
}

$userId = (int)$_SESSION['user_id'];

try {
    if ($email !== null && $email !== '') {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
        $check->execute([$email, $userId]);

        if ($check->fetch()) {
            http_response_code(409);
            echo json_encode([
                "success" => false,
                "message" => "That email is already in use."
            ]);
            exit;
        }
    }

    if ($username !== null && $username !== '') {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1");
        $check->execute([$username, $userId]);

        if ($check->fetch()) {
            http_response_code(409);
            echo json_encode([
                "success" => false,
                "message" => "That username is already in use."
            ]);
            exit;
        }
    }

    $fields = [];
    $params = [];

    if ($name !== null) {
        $fields[] = "name = ?";
        $params[] = ($name === '') ? null : $name;
    }

    if ($email !== null) {
        $fields[] = "email = ?";
        $params[] = ($email === '') ? null : $email;
    }

    if ($username !== null) {
        $fields[] = "username = ?";
        $params[] = ($username === '') ? null : $username;
    }

    if ($bio !== null) {
        $fields[] = "bio = ?";
        $params[] = ($bio === '') ? null : $bio;
    }

    if ($pronouns !== null) {
        $fields[] = "pronouns = ?";
        $params[] = ($pronouns === '') ? null : $pronouns;
    }

    if ($interests !== null) {
        $fields[] = "interests = ?";
        $params[] = ($interests === '') ? null : $interests;
    }

    if ($phone !== null) {
        $fields[] = "phone = ?";
        $params[] = ($phone === '') ? null : $phone;
    }

    if ($date_of_birth !== null) {
        $fields[] = "date_of_birth = ?";
        $params[] = ($date_of_birth === '') ? null : $date_of_birth;
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No valid fields to update."
        ]);
        exit;
    }

    $params[] = $userId;

    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $stmt = $conn->prepare("
        SELECT id, name, username, email, bio, pronouns, interests, phone, date_of_birth
        FROM users
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['name'] = $updated['name'];
    $_SESSION['email'] = $updated['email'];
    $_SESSION['username'] = $updated['username'];

    echo json_encode([
        "success" => true,
        "message" => "Profile updated.",
        "data" => [
            "id" => (int)$updated['id'],
            "name" => $updated['name'],
            "username" => $updated['username'],
            "email" => $updated['email'],
            "bio" => $updated['bio'],
            "pronouns" => $updated['pronouns'],
            "interests" => $updated['interests'],
            "phone" => $updated['phone'],
            "date_of_birth" => $updated['date_of_birth']
        ]
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error."
    ]);
}