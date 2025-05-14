<?php include('../includes/init.php'); ?>
<?php
$product_id = $_GET['product_id'];
$rs = mysqli_query($db_connection, 'SELECT * from tblproducts WHERE product_id=' . $product_id);
$rw = mysqli_fetch_assoc($rs);
echo json_encode($rw);
