<?php
if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['accountid'])) {
    if (file_exists('dbconfig.php')) {
        include_once('dbconfig.php');
    }
    if (file_exists('includes/dbconfig.php')) {
        include_once('includes/dbconfig.php');
    }
    if (file_exists('../includes/dbconfig.php')) {
        include_once('../includes/dbconfig.php');
    }
} else {
    header('location: ../');
    exit(0);
}
