<?php

header('Content-Type: application/json; charset=utf-8');

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized. Please log in."
    ]);
    exit;
}