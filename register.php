<?php

require 'database.php';
$new_username = $_POST['new_username'];
$new_password = $_POST['new_password'];

// Check if the new username is already used
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
$stmt->bind_param('s', $new_username);
$stmt->execute();
$stmt->bind_result($cnt);
$stmt->fetch();
$stmt->close();

// Alow to create the username only when it is not used
if($cnt == 0){
	// New username is not used
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
  $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
	  exit;
  }
  $stmt->bind_param('ss', $new_username, $hashed_password);
  $stmt->execute();
  $stmt->close();

	// Redirect to the portal
  session_start();
  $_SESSION['user_id'] = $new_username;
  $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); // generate a 32-byte random string
	header("Location: news_portal.php");
  exit;
} else{
	// New username is used
	header("Location: log_in.html");
  exit;
}
?>
