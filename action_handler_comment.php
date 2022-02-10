<?php
session_start();
$comment_id = $_POST['comment_id'];
$comment_action = $_POST['comment_action'];

# Choose what to do with the story depending on the chosen action
if ($comment_action == "Delete a comment"){   # Deleting a comment
  require 'database.php';
  session_start();
  $current_user_id = $_SESSION['user_id'];

  // Make sure the safety against CSRF
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }

  // Get the username for this story
  $stmt = $mysqli->prepare("select COUNT(*), username from comments where id=?");
  if(!$stmt){
  	printf("Query Prep Failed: %s\n", $mysqli->error);
  	exit;
  }
  $stmt->bind_param('s', $comment_id);
  $stmt->execute();
  $stmt->bind_result($cnt, $comment_user_id);
  $stmt->fetch();

  // Check if the logged-in user is authorized to delete this comment
  if($cnt == 1 && $current_user_id == $comment_user_id){
  	// User authentication succeeded
    $stmt->close();

    // Delete the comment in the database
    $stmt = $mysqli->prepare("delete from comments where id=?");
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
  	  exit;
    }
    $stmt->bind_param('s', $comment_id);
    $stmt->execute();
    $stmt->close();
    header("Location: comments_portal.php");
    exit;

  } else{
  	// User not authorized to delete this file
    header("Location: comments_portal.php");
    exit;
  }

} elseif ($comment_action == "Edit a comment"){   # Editing a comment
  header("Location: edit_comment.php?comment_id=$comment_id");
  exit;
}
?>
