<!DOCTYPE HTML>
<html lang="en">
<head><title>Stories</title></head>
<body>

  <!-- Log in functionality -->
  <p>
    If you are logged out and want to log in, click here:
    <a href="log_in.php">
      <button>Log in</button>
    </a>
  </p>

  <!-- Log out functionality -->
  <form name="input" action="log_out.php" method="get">
    <p>
      If you are logged_in, log out here:
      <input type="submit" value="Log out">
    </p>
  </form>

  <!-- Post a story -->
  <form name="input" action="post.php" method="post">
    <p>
      Post a story from here:
      <input type="submit" value="Post a story">
    </p>
  </form>

  <!-- Display all the stories-->
  <form action="action_handler.php" method="POST">
    Below are all the stories ordered by its popularity.<br>

    <?php
    require 'database.php';

    // Prepare a query and execute it
    $stmt = $mysqli->prepare("select id, title from stories order by visit desc");
    if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }
    $stmt->execute();

    // Get a result as an array
    $result = $stmt->get_result();
    echo "<ul>\n";
    while($row = $result->fetch_assoc()){
      echo '<input type="radio" name="story_id"';
      echo ' value= "'. htmlentities($row["id"]) . '">' . htmlentities($row["title"]) . ' <br>' . "\n";
    }
    echo "</ul>\n";

    $stmt->close();
    ?>
    <input type="submit" name="story_action" value="View a story"/>
    <input type="submit" name="story_action" value="View comments"/>
  </form>

  <!-- Display all the stories written by the user -->
  <form action="action_handler.php" method="POST">
    Below are all the stories you wrote.<br>

    <?php
    require 'database.php';

    // Get user id
    session_start();
    $user_id = $_SESSION['user_id'];

    // Prepare a query and execute it
    $stmt = $mysqli->prepare("select id, title from stories where username=?");
    if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }
    $stmt->bind_param('s', $user_id);
    $stmt->execute();

    // Get a result as an array
    $result = $stmt->get_result();
    echo "<ul>\n";
    while($row = $result->fetch_assoc()){
      echo '<input type="radio" name="story_id"';
      echo ' value= "'. htmlentities($row["id"]) . '">' . htmlentities($row["title"]) . ' <br>' . "\n";
    }
    echo "</ul>\n";

    $stmt->close();
    ?>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <input type="submit" name="story_action" value="View a story"/>
    <input type="submit" name="story_action" value="Delete a story"/>
    <input type="submit" name="story_action" value="Edit a story"/>
    <input type="submit" name="story_action" value="View comments"/>
  </form>
</body>
