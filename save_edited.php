<?php
require 'database.php';
session_start();
$current_user_id = $_SESSION['user_id'];
$story_id = $_POST['story_id'];
$title = (string) $_POST['title'];
$body = (string) $_POST['body'];
$link = (string) $_POST['link'];

// Get the username for this story
$stmt = $mysqli->prepare("select COUNT(*), username from stories where id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $story_id);
$stmt->execute();
$stmt->bind_result($cnt, $story_user_id);
$stmt->fetch();
$stmt->close();

// Check if the logged-in user is authorized to delete this story
if($cnt == 1 && $current_user_id == $story_user_id){

  // Make sure the safety against CSRF
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }

  // Update the edited story
  $stmt = $mysqli->prepare("update stories set body=?, title=?, link=? where id=?");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param('ssss', $body, $title, $link, $story_id);
  $stmt->execute();
  $stmt->close();
  header("Location: news_portal.php");
  exit;

} else{
	// User not authorized to delete this file
  header("Location: news_portal.php");
  exit;
}

?>
