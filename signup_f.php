<?php

include_once("db_connect.php");
require_once("hw4utils.php");

// print_r($_POST);


$registered = registerUser($db, $_POST);
if ($registered == FALSE) {
	print("<P>You already have an account</P>");
}
else {
	registerUser($db, $_POST);
}

//addUser($db, $login, $pass,CURRENT_TIMESTAMP, $email);

?>
