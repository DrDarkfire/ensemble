<?php
// brandon
session_start();
$_SESSION['uid'] = 1;
include_once("db_connect.php");
$songName = $_POST['songName']
$trackName = $_POST['songURI']
$queryUser = "INSERT INTO Tracks VALUES (null, '$songName', '$songURI')"
$resultUser = $db->query($queryUser)
?>