<?php
header("Content-Type: application/json");
require_once "../db.php";

$user_id = isset($_GET["user_id"]) ? intval($_GET["user_id"]) : 0;

if (!$user_id) {
    http_response_code(400);
    echo json_encode(["error" => "user_id is required"]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT f.id, f.attraction_id, f.type, a.name, a.image_url FROM favorites f JOIN attractions a ON f.attraction_id = a.id WHERE f.user_id = :user_id ORDER BY f.created_at DESC");
    $stmt->execute([":user_id" => $user_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch favorites"]);
}
?>