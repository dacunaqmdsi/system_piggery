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


// Sum feed expenses per month (categoryid=6)
$query = "
    SELECT DATE_FORMAT(expense_date, '%b') AS month, SUM(amount) AS total_feed
    FROM tblexpense
    WHERE categoryid = 6
    GROUP BY month
    ORDER BY MIN(expense_date)
";

$result = $db_connection->query($query);

$months = [];
$feedData = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $feedData[] = (float)$row['total_feed'];
    }
    echo json_encode([
        'months' => $months,
        'feedData' => $feedData
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}

$db_connection->close();
