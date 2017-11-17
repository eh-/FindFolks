<?php
//check for session
session_start();
if(isset($_SESSION['username'])) {}
else {
    header("Location: /index.php");
    die();
}
?>

<!DOCTYPE html>
<html>
<body>

<form action="./phpScripts/createGroup.php" method="post">
  
  Group Name: <input type="text" name="groupname" pattern="{3,20}" required><br>
  Description: <textarea name="description" cols="50" rows="3" pattern="{3,100}" required></textarea><br>
  Category: <input type="text" name="category" pattern="{3,20}" required><br>
  Keyword: <input type="text" name="keyword" pattern="{3,20}" required><br>
  <input type="submit" value="Create Group">
</form>

</body>
</html>
