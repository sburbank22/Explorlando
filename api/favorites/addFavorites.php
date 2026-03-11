<?php
header("Content-Type: application/json");
require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = isset($data["user_id"]) ? intval($data["user_id"]) : 0;
$attraction_id = isset($data["attraction_id"]) ? intval($data["attraction_id"]) : 0;
$type = (isset($data["type"]) && $data["type"] === "saved") ? "saved" : "favorite";

if (!$user_id || !$attraction_id) {
    http_response_code(400);
    echo json_encode(["error" => "user_id and attraction_id are required"]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, attraction_id, type) VALUES (:user_id, :attraction_id, :type)");
    $stmt->execute([":user_id" => $user_id, ":attraction_id" => $attraction_id, ":type" => $type]);
    echo json_encode(["message" => "Favorite added"]);
} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        http_response_code(409);
        echo json_encode(["error" => "Already favorited"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to add favorite"]);
    }
}
?>