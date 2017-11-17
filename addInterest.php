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

<form action="./phpScripts/addInterest.php" method="post">
  
  Category: <input type="text" name="category" pattern="{3,20}" required><br>
  Keyword: <input type="text" name="keyword" pattern="{3,20}" required><br>
  <input type="submit" value="Add Interest">
</form>

</body>
</html>