<?php
//Author: Mai Trinh
session_start();
?>

<?php
include_once("db_connect.php");
include_once("hw4utils.php");

$uid = $_SESSION['uid'];
$username = $_POST['username'];
$currentpw = $_POST['currentpw'];
$newpw = $_POST['newpw'];
$email = $_POST['email'];
$bdate = $_POST['bdate'];


$query = "SELECT * FROM user WHERE uid=$uid;";
$res = $db->query($query);
$result = $res->fetch();

$hashCurrentPw = hash('md5', $currentpw);

if ($hashCurrentPw != $result['passHash']) {
	header("Location: http://www.cs.gettysburg.edu/~tibech01/cs360/ensemble/updateProfile.php");
}
else {
	$update = updateProfile($db, $uid, $username, $newpw, $email, $bdate);

	if ($update == 1) {
		header("Location: http://www.cs.gettysburg.edu/~tibech01/cs360/ensemble/user.php");
	}
	else {
		print("<P>Your information was not updated. Please try again. </P>");
	}
}


?>
