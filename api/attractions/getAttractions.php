<?php
/*
  Attractions API endpoint for our group project backend.
  Returns all attractions from the database as JSON.

  Optional query parameters:
    ?featured=true      returns two featured attractions based on current quarter
    ?category=VALUE     filters results by category (ex: Outdoors, Dining)

  Example usage:
    api/attractions/getAttractions.php
    api/attractions/getAttractions.php?featured=true
    api/attractions/getAttractions.php?category=Outdoors
*/

header("Content-Type: application/json");

// db.php doesn't stop the script if the connection fails, it just prints an error.
// ob_start lets us catch that output and throw it away so we don't get
// two JSON responses mixed together if something goes wrong.
ob_start();
require_once "../db.php";
ob_end_clean();

// Check that the database connection actually worked before trying to use it
if (!isset($conn)) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection unavailable"
    ]);
    exit;
}

// Read in the optional query parameters from the URL
$featured = isset($_GET['featured']) ? $_GET['featured'] : null;
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

// The only valid value for featured is the string "true", reject anything else
if ($featured !== null && $featured !== 'true') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid value for 'featured'. Accepted value: true"
    ]);
    exit;
}

// Make sure category isn't empty and doesn't have any weird characters that could cause issues
if ($category !== null && (strlen($category) === 0 || !preg_match('/^[a-zA-Z0-9 \-]+$/', $category))) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid value for 'category'. Use letters, numbers, spaces, or hyphens only."
    ]);
    exit;
}

// Temporary category mapping since the database schema does not currently include categories.
// Each attraction ID maps to a category. Update this if new attractions are added.
$categoryMap = [
    1 => "Wildlife",
    2 => "Dining",
    3 => "Outdoors",
    4 => "Outdoors"
];

// Pull all attractions from the database using a prepared statement
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

// Add the category field to each attraction row using the map above
foreach ($attractions as &$row) {
    $row['category'] = isset($categoryMap[$row['id']]) ? $categoryMap[$row['id']] : null;
}
unset($row); // need to unset the reference after a foreach by reference

// If featured=true was passed in, filter down to just the two featured attractions.
// Since we don't have a featured column in the database, we rotate which two attractions
// are featured based on the current quarter of the year so it changes over time.
//   Q1 (Jan-Mar) -> attractions at index 0 and 1
//   Q2 (Apr-Jun) -> attractions at index 1 and 2
//   Q3 (Jul-Sep) -> attractions at index 2 and 3
//   Q4 (Oct-Dec) -> attractions at index 3 and 0
if ($featured === 'true') {
    $total = count($attractions);

    if ($total === 0) {
        echo json_encode(["status" => "success", "data" => []]);
        exit;
    }

    $quarter  = (int)(((int)date('n') - 1) / 3); // gives us 0, 1, 2, or 3
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

// If a category was passed in, filter results down to only that category
if ($category !== null) {
    $categoryLower = strtolower($category);
    $attractions   = array_values(
        array_filter($attractions, function ($row) use ($categoryLower) {
            return strtolower((string)$row['category']) === $categoryLower;
        })
    );
}

// Send back the results as JSON
echo json_encode([
    "status" => "success",
    "data"   => $attractions
]);
