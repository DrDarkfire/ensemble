<?php
// mysql connection script 
$host="ada.cc.gettysburg.edu";
$dbase="s20_trinma01";
$user="trinma01";
$pass="trinma01";

try {
	$db = new PDO("mysql:host=$host;dbname=$dbase", $user,$pass);

}
catch(PDOException $e) {
	die("Error connecting to MYSQL database" + $e->getMessage());
}
?>
