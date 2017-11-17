<?php
//check for session
session_start();

$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 $query = "Select count(*) From belongs_to Where username = ? And authorized = 1";
 if($stmt = mysqli_prepare($conn, $query)){
	 mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
	 mysqli_stmt_execute($stmt);
	 mysqli_stmt_bind_result($stmt, $res);
	 mysqli_stmt_fetch($stmt);
	 mysqli_stmt_close($stmt);
	 if(isset($_SESSION['username']) and $res > 0) {}
	 else {
		 header("Location: /index.php");
		 die();
	 }
 }
?>

<!DOCTYPE html>
<html>
<body>

<form action="./phpScripts/createEvent.php" method="post">
  Group Name: <select name="groupid">
  <?php 
  $hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
  $conn = mysqli_connect($hn, $un, $pw, $db);
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  $query = "Select group_id, group_name From a_group Natural Join belongs_to Where username = ? And authorized = 1";
  if($stmt2 = mysqli_prepare($conn, $query)){
	  mysqli_stmt_bind_param($stmt2, "s", $_SESSION['username']);
	  mysqli_stmt_execute($stmt2);
	  mysqli_stmt_bind_result($stmt2, $groupid, $groupname);
	  while(mysqli_stmt_fetch($stmt2)){
		  echo "<option value = ".$groupid.">".$groupname."</option>";
	  }
	  mysqli_stmt_close($stmt2);
  }
  ?>
  </select>
  
  Event Name: <input type="text" name="eventname" pattern="{3,100}" required><br>
  Description: <textarea name="description" cols="50" rows="3" pattern="{3,100}" required></textarea><br>
  Start Time (mm/dd/yyyy --:-- AM/PM): <input type="datetime-local" name="starttime" required><br>
  End Time (mm/dd/yyyy --:-- AM/PM): <input type="datetime-local" name="endtime" required><br>
  Location Name: <select name="location">
	<?php
	$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
  $conn = mysqli_connect($hn, $un, $pw, $db);
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  $query = "Select location_name, zipcode From location";
  if($stmt = mysqli_prepare($conn, $query)){
	  mysqli_stmt_execute($stmt);
	  mysqli_stmt_bind_result($stmt, $locname, $zipcode);
	  while(mysqli_stmt_fetch($stmt)){
		  echo "<option value = ".$locname.":".$zipcode.">".$locname.", ".$zipcode."</option>";
	  }
	  mysqli_stmt_close($stmt);
  }
	?>
	</select>
	<a href="/addLocation.php">Add a Location</a>
	<br>
  
  <input type="submit" value="Create Event">
</form>

</body>
</html>