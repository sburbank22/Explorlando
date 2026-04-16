<?php
session_start();
header("Content-Type: application/json");
require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION["user_id"] ?? 0;
$attraction_id = isset($data["attraction_id"]) ? intval($data["attraction_id"]) : 0;
$type = "saved";

if (!$user_id) {
    http_response_code(401);
    echo json_encode(["error" => "You must be logged in to save attractions"]);
    exit;
}

if (!$attraction_id) {
    http_response_code(400);
    echo json_encode(["error" => "attraction_id is required"]);
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO favorites (user_id, attraction_id, type)
        VALUES (:user_id, :attraction_id, :type)
    ");
    $stmt->execute([
        ":user_id" => $user_id,
        ":attraction_id" => $attraction_id,
        ":type" => $type
    ]);

   echo json_encode([
    "success" => true,
    "message" => "Attraction saved"
]);

} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        http_response_code(409);
        echo json_encode(["error" => "Attraction already saved"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to save attraction"]);
    }
}
?>