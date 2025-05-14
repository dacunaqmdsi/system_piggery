<?php include('../includes/init.php'); ?>
<?php
$customerid = $_GET['customerid'];
$rs = mysqli_query($db_connection, 'SELECT customerid, name, phone, email, address, notes from tblcustomer WHERE customerid=' . $customerid);
$rw = mysqli_fetch_assoc($rs);
echo json_encode($rw);
