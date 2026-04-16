<?php
session_start();
header("Content-Type: application/json");
require_once "../db.php";

$user_id = $_SESSION["user_id"] ?? 0;

if (!$user_id) {
    http_response_code(401);
    echo json_encode(["error" => "You must be logged in to view favorites"]);
    exit;
}

try {
$stmt = $conn->prepare("
    SELECT f.id, f.attraction_id, f.type, a.name, a.image_url
    FROM favorites f
    LEFT JOIN attractions a ON f.attraction_id = a.id
    WHERE f.user_id = :user_id
    ORDER BY f.created_at DESC
");

    $stmt->execute([":user_id" => $user_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch favorites"]);
}
?>