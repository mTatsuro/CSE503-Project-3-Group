<?php
require 'database.php';
session_start();
$user_id = $_SESSION['user_id'];
$story_id = $_POST['story_id'];
$title = (string) $_POST['title'];
$body = (string) $_POST['body'];
$link = (string) $_POST['link'];

// Make sure the username is valid
if( !preg_match('/^[\w_\-]+$/', $user_id) ){
	echo "Invalid username";
	exit;
}

// Make sure the safety against CSRF
if(!hash_equals($_SESSION['token'], $_POST['token'])){
  die("Request forgery detected");
}

// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
$full_path = sprintf("img/%s", $filename);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){   # when file is not chosen

  // Insert the new story
  $stmt = $mysqli->prepare("insert into stories (body, title, link, username) values (?, ?, ?, ?)");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param('ssss', $body, $title, $link, $user_id);
  $stmt->execute();
  $stmt->close();
  header("Location: news_portal.php");
  exit;

} else{   # file is chosen
  if(! move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
  	header("Location: upload_failure.html");
  	exit;
  }

  // Insert the new story
  $stmt = $mysqli->prepare("insert into stories (body, title, link, username, image) values (?, ?, ?, ?, ?)");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param('sssss', $body, $title, $link, $user_id, $filename);
  $stmt->execute();
  $stmt->close();
  header("Location: news_portal.php");
  exit;
}

?>
