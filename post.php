<!DOCTYPE HTML>
<html lang="en">
<head><title>Post a story</title></head>
<body>
  <form enctype="multipart/form-data" name="input" action="save_posted.php" method="post">
    <?php
    require 'database.php';
    session_start();
    $user = $_SESSION['user_id'];

    // Check if the user is logged in
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();
    if($cnt == 1){

      // Display the editor
      echo '<p>Title: <input type="text" name="title" /></p>';
      echo '<p>Content:<br>';
      echo '<textarea name="body" rows="20" cols="70"></textarea><br></p>';
      echo '<p>Link: <input type="text" name="link" /></p>';
      echo '<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />';
      echo '<label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />';
      echo '<input type="hidden" name="story_id" value="$user" />';

    } else{
    	// This user is not logged in
      header("Location: news_portal.php");
      exit;
    }
    ?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <p><input type="submit" value="Save"></p>
  </form>
</body>
