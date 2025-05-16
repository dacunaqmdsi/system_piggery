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


// Get last 6 months (year-month) and total revenue per month
$query = "
    SELECT DATE_FORMAT(sale_date, '%b') AS month, 
           SUM(qty * price) AS total_revenue
    FROM tblsales
    WHERE is_void = 'No'
      AND sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(sale_date), MONTH(sale_date)
    ORDER BY YEAR(sale_date), MONTH(sale_date)
";

$result = $db_connection->query($query);

$months = [];
$salesData = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $salesData[] = (float)$row['total_revenue'];
    }
    echo json_encode([
        'months' => $months,
        'salesData' => $salesData
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}

$db_connection->close();
