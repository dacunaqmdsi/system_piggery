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
$query = $db_connection->query("
    SELECT activity, description, created_at 
    FROM tblaudittrail 
    ORDER BY created_at DESC 
    LIMIT 10
");

$notifications = [];
while ($row = $query->fetch_assoc()) {
    $notifications[] = $row;
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($notifications);
