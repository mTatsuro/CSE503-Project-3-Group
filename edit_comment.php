<!DOCTYPE HTML>
<html lang="en">
<head><title>Text editor</title></head>
<body>
  <form name="input" action="save_edited_comment.php" method="post">
    <?php
    require 'database.php';
    session_start();
    $current_user_id = $_SESSION['user_id'];
    $comment_id = $_GET['comment_id'];

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

    // Check if the logged-in user is authorized to edit this comment
    if($cnt == 1 && $current_user_id == $comment_user_id){
    	// User authentication succeeded
      $stmt->close();

      // Get the comment info in the database
      $stmt = $mysqli->prepare("select body from comments where id=?");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
    	  exit;
      }
      $stmt->bind_param('s', $comment_id);
      $stmt->execute();
      $stmt->bind_result($body);
      $stmt->fetch();
      $stmt->close();

    } else{
    	// User not authorized to delete this comment
      header("Location: comments_portal.php");
      exit;
    }

    // Display the editor
    echo '<p>Content:<br>';
    echo '<textarea name="body" rows="20" cols="70">'.htmlentities($body).'</textarea><br></p>';
    echo '<input type="hidden" name="comment_id" value="'.htmlentities($comment_id).'" />';
    ?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <p><input type="submit" value="Save"></p>
  </form>
</body>
