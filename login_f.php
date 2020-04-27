<?php

//print_r($_POST);

include_once("db_connect.php");
include_once("hw4utils.php");

$username = $_POST['username'];
$pass = $_POST['password'];

//print_r($_POST);
//print_r($pass);

$check = checkUser($db, $username, $pass);

if ($check == -1) {
	print("<P>Cannot find your information. Please sign up.</P>");
}
else if ($check == -3) {
	print("<P>Your password doesn't match</P>");
}
else if ($check == -2) {
	print("<P>Check your email to verify your account</P>");
}
else {
	$qStr = "SELECT uid FROM user WHERE username = '$username';";
	$qRes = $db->query($qStr);
	$row = $qRes->fetch();
	$uid = $row['uid'];
	header("Location: http://www.cs.gettysburg.edu/~tibech01/cs360/ensemble/user.php?&uid=$uid");
}

?>
