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

<form action="./phpScripts/joinGroup.php" method="post">
  Group Name: <select name="groupid">
  <?php 
  $hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
  $conn = mysqli_connect($hn, $un, $pw, $db);
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  $query = "Select group_id, group_name From a_group Where (group_id, group_name) NOT IN (Select group_id, group_name From a_group Natural Join belongs_to Where username = ?)";
  if($stmt = mysqli_prepare($conn, $query)){
	  mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
	  mysqli_stmt_execute($stmt);
	  mysqli_stmt_bind_result($stmt, $groupid, $groupname);
	  while(mysqli_stmt_fetch($stmt)){
		  echo "<option value = ".$groupid.">".$groupname."</option>";
	  }
	  mysqli_stmt_close($stmt2);
  }
  ?>
  </select>
  
  <input type="submit" value="Join Group">
</form>

</body>
</html>