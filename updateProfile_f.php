<?php
include_once("db_connect.php");
require_once("hw4utils.php");

$username = $_POST['username'];
$email = $_POST['email'];
$bdate = $_POST['bdate'];

updateProfile($username, $email, $bdate);
?>