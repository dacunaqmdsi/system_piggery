<?php include('../includes/init.php'); ?>
<?php
$accountid = $_GET['accountid'];
$rs = mysqli_query($db_connection, 'SELECT accountid, username, account_type, account_password from tblaccounts WHERE accountid=' . $accountid);
$rw = mysqli_fetch_assoc($rs);
echo json_encode($rw);
