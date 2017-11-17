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

<form action="./phpScripts/searchInterest.php" method="post">
  Interest: <select name="searchInterest">
  <?php 
  $hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
  $conn = mysqli_connect($hn, $un, $pw, $db);
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  $query = "Select category, keyword From interested_in Where username = ?";
  if($stmt = mysqli_prepare($conn, $query)){
	  mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
	  mysqli_stmt_execute($stmt);
	  mysqli_stmt_bind_result($stmt, $category, $keyword);
	  while(mysqli_stmt_fetch($stmt)){
		  echo "<option value = ".$category.":".$keyword.">".$category.":".$keyword."</option>";
	  }
	  mysqli_stmt_close($stmt);
  }
  ?>
  </select>
  
  <input type="submit" value="Search Events">
</form>

</body>
</html>