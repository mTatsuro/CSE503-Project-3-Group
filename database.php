<?php
// Content of database.php

$mysqli = new mysqli('localhost', 'phpuser', 'wustl1853', 'news');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>
