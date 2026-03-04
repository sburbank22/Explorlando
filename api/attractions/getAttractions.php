<?php

header("Content-Type: application/json");

require_once "../db.php";

$stmt = $conn->query("SELECT * FROM attractions");
$attractions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($attractions);

?>