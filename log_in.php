<?php
// This is a *good* example of how you can implement password-based user authentication in your web application.

require 'database.php';

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), password FROM users WHERE username=?");

// Bind the parameter
$stmt->bind_param('s', $user);
$user = (string) $_POST['username'];
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $pwd_hash);
$stmt->fetch();
$stmt->close();

$pwd_guess = (string) $_POST['password'];
// Compare the submitted password to the actual password hash

if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	// Login succeeded!
	session_start();
	$_SESSION['user_id'] = $user;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); // generate a 32-byte random string
	// Redirect to your target page
	header("Location: news_portal.php");
  exit;
} else{
	// Login failed; redirect back to the login screen
	header("Location: log_in.html");
  exit;
}
?>
