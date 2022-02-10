<!DOCTYPE HTML>
<html lang="en">
<head><title>Post a comment</title></head>
<body>
  <form name="input" action="save_posted_comments.php" method="post">
    <?php
    require 'database.php';
    session_start();
    $user = $_SESSION['user_id'];
    $story_id = $_POST['story_id'];

    // Check if the logged-in user is a valid user
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();
    if($cnt == 1){

      // Display the editor
      echo '<p>Content:<br>';
      echo '<textarea name="body" rows="20" cols="70"></textarea><br></p>';
      echo '<input type="hidden" name="story_id" value="'.$story_id.'" />';

    } else{
    	// User not authorized to delete this file
      header("Location: comments_portal.php");
      exit;
    }
    ?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <p><input type="submit" value="Save"></p>
  </form>
</body>
