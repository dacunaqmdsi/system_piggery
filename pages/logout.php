<?php
session_start();
include('../includes/dbconfig.php');
Audit($_SESSION['accountid'], 'Logout', 'Logout');
session_destroy();
session_write_close();
setcookie(session_name(), '', 0, '/');
header("location: ../");
