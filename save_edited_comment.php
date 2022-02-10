<?php
require 'database.php';
session_start();
$current_user_id = $_SESSION['user_id'];
$comment_id = $_POST['comment_id'];
$body = (string) $_POST['body'];

// Make sure the safety against CSRF
if(!hash_equals($_SESSION['token'], $_POST['token'])){
  die("Request forgery detected");
}

// Get the username for this comment
$stmt = $mysqli->prepare("select COUNT(*), username from comments where id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $comment_id);
$stmt->execute();
$stmt->bind_result($cnt, $comment_user_id);
$stmt->fetch();
$stmt->close();

// Check if the logged-in user is authorized to edit this comment
if($cnt == 1 && $current_user_id == $comment_user_id){

  // Update the edited comment
  $stmt = $mysqli->prepare("update comments set body=? where id=?");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param('ss', $body, $comment_id);
  $stmt->execute();
  $stmt->close();
  header("Location: comments_portal.php");
  exit;

} else{
	// User not authorized to delete this comment
  header("Location: comments_portal.php");
  exit;
}

?>
