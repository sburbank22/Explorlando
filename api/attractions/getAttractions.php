<?php
// Attractions API returns as JSON
// Optional params: ?featured=true, ?category=Outdoors

header("Content-Type: application/json");

// ob_start prevents db.php from printing its own error output before we can handle it
ob_start();
require_once "../db.php";
ob_end_clean();

if (!isset($conn)) {
    echo json_encode(["status" => "error", "message" => "Database connection unavailable"]);
    exit;
}

$featured = isset($_GET['featured']) ? $_GET['featured'] : null;
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

if ($featured !== null && $featured !== 'true') {
    echo json_encode(["status" => "error", "message" => "Invalid value for 'featured'. Accepted value: true"]);
    exit;
}

// Reject empty or unsafe category values
if ($category !== null && (strlen($category) === 0 || !preg_match('/^[a-zA-Z0-9 \-]+$/', $category))) {
    echo json_encode(["status" => "error", "message" => "Invalid value for 'category'. Use letters, numbers, spaces, or hyphens only."]);
    exit;
}

// Category map since the database doesn't have a category column yet
$categoryMap = [
    1 => "Wildlife",
    2 => "Dining",
    3 => "Outdoors",
    4 => "Outdoors"
];

try {
    $stmt = $conn->prepare("SELECT id, name, description, location, image_url FROM attractions");
    $stmt->execute();
    $attractions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database query failed"]);
    exit;
}

// Attach category to each row from the map
foreach ($attractions as &$row) {
    $row['category'] = isset($categoryMap[$row['id']]) ? $categoryMap[$row['id']] : null;
}
unset($row);

// Rotate featured attractions by quarter since there's no featured column in the DB
if ($featured === 'true') {
    $total = count($attractions);

    if ($total === 0) {
        echo json_encode(["status" => "success", "data" => []]);
        exit;
    }

    $quarter  = (int)(((int)date('n') - 1) / 3); // 0-3
    $idxA     = $quarter % $total;
    $idxB     = ($quarter + 1) % $total;

    $featuredIds = [
        $attractions[$idxA]['id'],
        $attractions[$idxB]['id']
    ];

    $attractions = array_values(
        array_filter($attractions, function ($row) use ($featuredIds) {
            return in_array($row['id'], $featuredIds, true);
        })
    );
}

if ($category !== null) {
    $categoryLower = strtolower($category);
    $attractions   = array_values(
        array_filter($attractions, function ($row) use ($categoryLower) {
            return strtolower((string)$row['category']) === $categoryLower;
        })
    );
}

echo json_encode(["status" => "success", "data" => $attractions]);
