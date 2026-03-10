<?php
header("Content-Type: application/json");
require_once "../db.php";

// logic here

echo json_encode(["message" => "Favorite added"]);
?>