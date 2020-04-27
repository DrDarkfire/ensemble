<?php
session_start();
?>

<?php
include_once("db_connect.php");

$uid = $_SESSION['uid'];
$fid = $_GET['id'];

$qStr = "INSERT INTO follow VALUE($uid, $fid);";
$qRes = $db->query($qStr);

print_r($qStr);

header("Location: http://www.cs.gettysburg.edu/~tibech01/cs360/ensemble/otherUser.php?&fid=$fid");





//print_r("<P>User $follower is following $followee </P>");
// redirect to the otherUser.php and access database again and then show message by modifying the DOM
?>
