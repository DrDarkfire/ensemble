<?php

include_once("db_connect.php");
require_once("hw4utils.php");

// print_r($_POST);


$registered = registerUser($db, $_POST);
if ($registered == FALSE) {
	print("<P>You already have an account</P>");
}
else {
	header("Location: http://www.cs.gettysburg.edu/~tibech01/cs360/ensemble/login.html");
}

//addUser($db, $login, $pass,CURRENT_TIMESTAMP, $email);

?>
