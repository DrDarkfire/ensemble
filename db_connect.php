
<?php
//Author:  Mai Trinh
// mysql connection script
$host="ada.cc.gettysburg.edu";
$dbase="s20_tibech01";
$user="tibech01";
$pass="tibech01";

try {
	$db = new PDO("mysql:host=$host;dbname=$dbase", $user,$pass);

}
catch(PDOException $e) {
	die("Error connecting to MYSQL database" + $e->getMessage());
}
?>
