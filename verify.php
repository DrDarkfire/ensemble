<?php
include_once("db_connect.php");
include_once("hw4utils.php");

//echo "verify called.";
//print_r($_GET['uid']);

$login = $_GET['uid'];
verifyEmail($db, $login);

?>
