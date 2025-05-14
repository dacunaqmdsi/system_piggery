<?php include('../includes/init.php'); ?>
<?php
$expenseid = $_GET['expenseid'];
$rs = mysqli_query($db_connection, 'SELECT * from tblexpense WHERE expenseid=' . $expenseid);
$rw = mysqli_fetch_assoc($rs);
echo json_encode($rw);
