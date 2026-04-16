<?php
session_start();
header("Content-Type: application/json");
require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION["user_id"] ?? 0;
$attraction_id = isset($data["attraction_id"]) ? intval($data["attraction_id"]) : 0;

if (!$user_id) {
    http_response_code(401);
    echo json_encode(["error" => "You must be logged in to remove saved attractions"]);
    exit;
}

if (!$attraction_id) {
    http_response_code(400);
    echo json_encode(["error" => "attraction_id is required"]);
    exit;
}

try {
    $stmt = $conn->prepare("
        DELETE FROM favorites
        WHERE user_id = :user_id
        AND attraction_id = :attraction_id
    ");
    $stmt->execute([
        ":user_id" => $user_id,
        ":attraction_id" => $attraction_id
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Attraction removed"
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to remove attraction"]);
}
?>