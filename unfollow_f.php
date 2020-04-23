<?php
include_once("db_connect.php");
$_SESSION['uid'] = 4;


$uid = $_SESSION['uid'];
$fid = $_POST['unfollow_id'];
//print($uid);

$query = "DELETE FROM follow WHERE uid=$uid AND fid=$fid;";
$result = $db->query($query);

if ($result != FALSE) {
	header("Location: http://www.cs.gettysburg.edu/~trinma01/ensemble/profile.php");
}
else {
	print("There is an error unfollowing. Pleae try again");
}
?>
