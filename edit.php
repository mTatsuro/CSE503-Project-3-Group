<!DOCTYPE HTML>
<html lang="en">
<head><title>Text editor</title></head>
<body>
  <form name="input" action="save_edited.php" method="post">
    <?php
    require 'database.php';
    session_start();
    $current_user_id = $_SESSION['user_id'];
    $story_id = $_GET['story_id'];

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

    // Check if the logged-in user is authorized to edit this story
    if($cnt == 1 && $current_user_id == $story_user_id){
    	// User authentication succeeded
      $stmt->close();

      // Get the story info in the database
      $stmt = $mysqli->prepare("select body, title, link from stories where id=?");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
    	  exit;
      }
      $stmt->bind_param('s', $story_id);
      $stmt->execute();
      $stmt->bind_result($body, $title, $link);
      $stmt->fetch();
      $stmt->close();

    } else{
    	// User not authorized to delete this file
      header("Location: news_portal.php");
      exit;
    }

    // Display the editor
    echo '<p>Title: <input type="text" name="title" value="'.htmlentities($title).'"/></p>';
    echo '<p>Content:<br>';
    echo '<textarea name="body" rows="20" cols="70">'.htmlentities($body).'</textarea><br></p>';
    echo '<p>Link: <input type="text" name="link" value="'.htmlentities($link).'"/></p>';
    echo '<input type="hidden" name="story_id" value="'.$story_id.'" />';
    ?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <p><input type="submit" value="Save"></p>
  </form>
</body>
