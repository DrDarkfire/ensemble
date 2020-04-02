<?php

include_once("db_connect.php");
require_once("hw4utils.php");


$username = $_POST['username'];
$email = $_POST['email'];
$bdate = $_POST['bdate'];

$update = updateProfile($db, $username, $email, $bdate);

if ($update == 1) {
	header("Location: http://www.cs.gettysburg.edu/~trinma01/project/ensemble-master/user.html");
}
else {
	print("Your information was not updated. Please try again.");
}


?>
