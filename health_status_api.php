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

// 1. Total pigs overall (sum of count_)
$total_pigs = 0;
$totals_query = $db_connection->query("SELECT SUM(count_) AS total FROM tblinventory");
if ($row = $totals_query->fetch_assoc()) {
    $total_pigs = (int)$row['total'];
}

// 2. Count pigs per symptom (Health status)
$symptom_counts = [
    'Healthy' => 0,
    'Sick' => 0,
    'Quarantine' => 0
];

// Fetch symptom IDs for mapping (adjust as per your symptom names/ids)
$symptom_map = [];
$sym_query = $db_connection->query("SELECT symptom_id, symptom FROM tblsymptom");
while ($r = $sym_query->fetch_assoc()) {
    $symptom_map[$r['symptom']] = $r['symptom_id'];
}

// Count pigs by symptom (health status) from monitor table
$monitor_query = $db_connection->query("
    SELECT s.symptom, COUNT(*) AS affected_count
    FROM tblmonitor m
    JOIN tblsymptom s ON m.symptom_id = s.symptom_id
    GROUP BY s.symptom
");

while ($r = $monitor_query->fetch_assoc()) {
    $symptom_name = $r['symptom'];
    $count = (int)$r['affected_count'];

    if (array_key_exists($symptom_name, $symptom_counts)) {
        $symptom_counts[$symptom_name] = $count;
    }
}

// Assume healthy pigs = total pigs - (sick + quarantine)
$sick_quarantine = $symptom_counts['Sick'] + $symptom_counts['Quarantine'];
$symptom_counts['Healthy'] = max($total_pigs - $sick_quarantine, 0);

// Output as JSON for JS to consume
echo json_encode($symptom_counts);
