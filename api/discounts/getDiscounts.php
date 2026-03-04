<?php
header("Content-Type: application/json");
require_once "../db.php";

$stmt = $conn->query("SELECT * FROM discounts");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>