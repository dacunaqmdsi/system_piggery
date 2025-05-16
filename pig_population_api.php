<?php
header('Content-Type: application/json');
if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['accountid'])) {
    if (file_exists('includes/dbconfig.php')) {
        include_once('includes/dbconfig.php');
    }
} else {
    header('location: ./');
    exit(0);
}


// Query: get sum of pigs per month (abbreviated month name, e.g. Jan, Feb)
$query = "
    SELECT DATE_FORMAT(created_at, '%b') AS month, SUM(count_) AS total_pigs
    FROM tblinventory
    GROUP BY month
    ORDER BY MIN(created_at)
";

$result = $db_connection->query($query);

$months = [];
$population = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $population[] = (int)$row['total_pigs'];
    }
    echo json_encode([
        'months' => $months,
        'population' => $population
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}

$db_connection->close();
