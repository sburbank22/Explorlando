<?php
header("Content-Type: application/json");
require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = isset($data["user_id"]) ? intval($data["user_id"]) : 0;
$id = isset($data["id"]) ? intval($data["id"]) : 0;

if (!$user_id || !$id) {
    http_response_code(400);
    echo json_encode(["error" => "user_id and id are required"]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM favorites WHERE id = :id AND user_id = :user_id");
    $stmt->execute([":id" => $id, ":user_id" => $user_id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Favorite not found"]);
    } else {
        echo json_encode(["message" => "Favorite removed"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to remove favorite"]);
}
?>