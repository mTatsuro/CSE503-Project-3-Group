<?php
session_start();
session_destroy();
header('Location: news_portal.php');
exit;
?>
