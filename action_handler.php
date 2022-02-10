<?php
session_start();
$story_id = $_POST['story_id'];

$story_action = $_POST['story_action'];

# Choose what to do with the story depending on the chosen action
if ($story_action == "View a story"){   # Viewing a story
  header("Location: view.php?story_id=$story_id");
  exit;
} elseif ($story_action == "Delete a story"){   # Deleting a story
  require 'database.php';
  $current_user_id = $_SESSION['user_id'];

  // Make sure the safety against CSRF
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }

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

  // Check if the logged-in user is authorized to delete this story
  if($cnt == 1 && $current_user_id == $story_user_id){
  	// User authentication succeeded
    $stmt->close();

    // Delete the story in the database
    $stmt = $mysqli->prepare("delete from stories where id=?");
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
  	  exit;
    }
    $stmt->bind_param('s', $story_id);
    $stmt->execute();
    $stmt->close();
    header("Location: news_portal.php");
    exit;

  } else{
  	// User not authorized to delete this file
    header("Location: news_portal.php");
    exit;
  }

} elseif ($story_action == "Edit a story"){   # Editing a story
  header("Location: edit.php?story_id=$story_id");
  exit;
} elseif ($story_action == "View comments"){   # Viewing comments
  header("Location: comments_portal.php?story_id=$story_id");
  exit;
}
?>
