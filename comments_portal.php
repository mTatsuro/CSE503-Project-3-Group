<!DOCTYPE HTML>
<html lang="en">
<head><title>Comments</title>
  <style type="text/css">
  header{
  	font:16px/18px Verdana, sans-serif;
  }
  </style>
</head>
<body>
  <header>
    These are comments for the selected news.
  </header>

  <!-- Post a comment -->
  <form name="input" action="post_comment.php" method="post">
    <p>
      Post a comment for this news from here:
      <?php echo '<input type="hidden" name="story_id" value="'. $_GET['story_id'].'" />' ?>
      <input type="submit" value="Post a comment">
    </p>
  </form>

  <!-- Display all the comments-->
  <p>
    Below are all the comments for this story.<br>

    <?php
    require 'database.php';

    // Prepare a query and execute it
    $stmt = $mysqli->prepare("select id, body from comments where story_id=?");
    if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }
    $stmt->bind_param('s', $_GET['story_id']);
    $stmt->execute();

    // Get a result as an array
    $result = $stmt->get_result();
    echo "<ul>\n";
    while($row = $result->fetch_assoc()){
      echo htmlentities($row["body"]) . ' <br>' . "\n";
    }
    echo "</ul>\n";

    $stmt->close();
    ?>
  </p>

  <!-- Display all the comments written by the user -->
  <form action="action_handler_comment.php" method="POST">
    Below are all the comments you wrote.<br>

    <?php
    require 'database.php';

    // Get user id
    session_start();
    $user_id = $_SESSION['user_id'];

    // Prepare a query and execute it
    $stmt = $mysqli->prepare("select id, body from comments where username=? and story_id=?");
    if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }
    $stmt->bind_param('ss', $user_id, $_GET['story_id']);
    $stmt->execute();

    // Get a result as an array
    $result = $stmt->get_result();
    echo "<ul>\n";
    while($row = $result->fetch_assoc()){
      echo '<input type="radio" name="comment_id"';
      echo ' value= "'. htmlentities($row["id"]) . '">' . htmlentities($row["body"]) . ' <br>' . "\n";
    }
    echo "</ul>\n";

    $stmt->close();
    ?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <input type="submit" name="comment_action" value="Delete a comment"/>
    <input type="submit" name="comment_action" value="Edit a comment"/>
  </form>
</body>
