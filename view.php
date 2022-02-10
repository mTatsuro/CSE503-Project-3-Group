<!DOCTYPE HTML>
<html lang="en">
<head><title>View a story</title>
<style type="text/css">
header{
	font:16px/18px Verdana, sans-serif; /* default font */
}
</style>
</head>
<body>
  <?php
  require 'database.php';

  // Get the specified story from mysql
  $story_id = $_GET['story_id'];
  $stmt = $mysqli->prepare("select body, title, link, visit, image from stories where id=?");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
	  exit;
  }
  $stmt->bind_param('s', $story_id);
  $stmt->execute();

  // Get the story info and print it
  $stmt->bind_result($body, $title, $link, $visit, $image);
  echo "<ul>\n";
  while($stmt->fetch()){
    echo '<header>'.htmlentities($title).'</header><br>';
    echo '<img src="img/'.htmlentities($image).'" alt="Image not found" width="300" />';
    echo '<p>'.htmlentities($body).'</p><br>';
    echo '<a href="$link">'.htmlentities($link).'</a>';
    echo '<p>This story was visited '.htmlentities($visit).' times.';
  }
  echo "</ul>\n";
  $stmt->close();

  // Visit counter functionality
  $visit += 1;    # increase the number of visit by 1
  // Update the edited comment
  $stmt = $mysqli->prepare("update stories set visit=? where id=?");
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt->bind_param('ss', $visit, $story_id);
  $stmt->execute();
  $stmt->close();
  ?>


</body>
