<?php
//session_start();
$_SESSION['uid'] = 4;
$_SESSION['fid'] = 3;
?>

<?php
include_once("db_connect.php");

$uid = $_SESSION['uid'];
$fid = $_SESSION['fid'];

$qStr = "INSERT INTO follow VALUE($uid, $fid);";
$qRes = $db->query($qStr);

print_r($qStr);

header("Location: http://www.cs.gettysburg.edu/~trinma01/ensemble/otherUser.php");





//print_r("<P>User $follower is following $followee </P>");
// redirect to the otherUser.php and access database again and then show message by modifying the DOM
?>
