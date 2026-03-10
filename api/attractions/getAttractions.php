<?php
/*
API Endpoint: /api/attractions/getAttractions.php

Query parameters:
?featured=true      Returns rotating featured attractions
?category=VALUE     Filters attractions by category

Example:
api/attractions/getAttractions.php?featured=true
api/attractions/getAttractions.php?category=Outdoors
*/

header("Content-Type: application/json");

/* Buffer db.php output: it echoes its own JSON on connection failure and then
// falls through, which would produce a double-JSON response. Suppressing it
 here ensures only our structured error JSON is ever sent to the client. */
ob_start();
require_once "../db.php";
ob_end_clean();

// Guard against a failed DB connection (db.php doesn't exit on failure)
if (!isset($conn)) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection unavailable"
    ]);
    exit;
}

// Parameter validation

$featured = isset($_GET['featured']) ? $_GET['featured'] : null;
$category = isset($_GET['category']) ? trim($_GET['category'])  : null;

// 'featured' only accepts the string "true"
if ($featured !== null && $featured !== 'true') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid value for 'featured'. Accepted value: true"
    ]);
    exit;
}

// 'category' must be non-empty and contain only safe characters
if ($category !== null && (strlen($category) === 0 || !preg_match('/^[a-zA-Z0-9 \-]+$/', $category))) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid value for 'category'. Use letters, numbers, spaces, or hyphens only."
    ]);
    exit;
}

// Hardcoded category map (keyed by attraction id)
// Update this map whenever attractions are added or renamed in the database.
// This avoids requiring a schema change while still supporting category filtering.
$categoryMap = [
    1 => "Wildlife",
    2 => "Dining",
    3 => "Outdoors",
    4 => "Outdoors"
];

// Fetch all attractions using a prepared statement
try {
    $stmt = $conn->prepare(
        "SELECT id, name, description, location, image_url FROM attractions"
    );
    $stmt->execute();
    $attractions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database query failed"
    ]);
    exit;
}

// Attach the category to every row from the lookup map
foreach ($attractions as &$row) {
    $row['category'] = isset($categoryMap[$row['id']]) ? $categoryMap[$row['id']] : null;
}
unset($row); // break reference

// Filter: ?featured=true
// "Featured" rotates every calendar quarter so that two attractions are
// highlighted per three-month period without any schema changes.
//   Q1 (Jan–Mar) → ids at index 0, 1
//   Q2 (Apr–Jun) → ids at index 1, 2
//   Q3 (Jul–Sep) → ids at index 2, 3
//   Q4 (Oct–Dec) → ids at index 3, 0
if ($featured === 'true') {
    $total = count($attractions);

    if ($total === 0) {
        echo json_encode(["status" => "success", "data" => []]);
        exit;
    }

    $quarter  = (int)(((int)date('n') - 1) / 3); // 0–3
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

// Filter: ?category=VALUE
if ($category !== null) {
    $categoryLower = strtolower($category);
    $attractions   = array_values(
        array_filter($attractions, function ($row) use ($categoryLower) {
            return strtolower((string)$row['category']) === $categoryLower;
        })
    );
}

// Response
echo json_encode([
    "status" => "success",
    "data"   => $attractions
]);
