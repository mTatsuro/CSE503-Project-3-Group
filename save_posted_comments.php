<?php
require 'database.php';
session_start();
$user_id = $_SESSION['user_id'];
$story_id = $_POST['story_id'];
$body = (string) $_POST['body'];

// Make sure the safety against CSRF
if(!hash_equals($_SESSION['token'], $_POST['token'])){
  die("Request forgery detected");
}

// Insert the new story
$stmt = $mysqli->prepare("insert into comments (body, story_id, username) values (?, ?, ?)");
if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param('sss', $body, $story_id, $user_id);
$stmt->execute();
$stmt->close();
header("Location: comments_portal.php");
exit;

?>
