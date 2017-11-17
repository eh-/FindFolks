<?php  
session_start();
if(isset($_SESSION['username'])) {}
else {
    header("Location: /index.php");
    die();
}
  include './phpScripts/displayFriends.php';

  echo "<br>";
  echo "<form action='./phpScripts/addFriend.php' method='post'>";
  echo "<input type='text' name='friendRequest' required>";
  echo "<input type='submit' value='Send Friend Request'>";
  echo "</form>";
?>