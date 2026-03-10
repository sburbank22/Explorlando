<?php
header("Content-Type: application/json");
require_once "../db.php";

// login logic here

echo json_encode(["status" => "success"]);
?>