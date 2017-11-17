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

<form action="./phpScripts/signupEvent.php" method="post">
<?php
$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$index = 0;
echo "<h1>Upcoming Events to Sign Up </h1>";
echo "<table border = 1>";
echo "<tr>";
echo "<td> Event ID </td>";
echo "<td> Title </td>";
echo "<td> Description </td>";
echo "<td> Start Time</td>";
echo "<td> End Time </td>";
echo "<td> Location Name </td>";
echo "<td> Zipcode </td>";
echo "</tr>";
$query = "SELECT event_id, title, description, start_time, end_time, location_name, zipcode FROM belongs_to natural join organize natural join an_event Where username = ? AND event_id NOT IN (Select event_id from sign_up where username = ?) AND start_time > now()";
if($stmt = mysqli_prepare($conn, $query)){
	  mysqli_stmt_bind_param($stmt, "ss",$_SESSION['username'], $_SESSION['username']);
	  mysqli_stmt_execute($stmt);
	  mysqli_stmt_bind_result($stmt, $eventid, $title, $description, $starttime, $endtime, $locname, $zipcode);
	  while(mysqli_stmt_fetch($stmt)){
			$events[$index] = $eventid;
			$index = $index + 1;
		  echo "<tr>";
      echo "<td>" .$eventid ."</td>";
      echo "<td>" .$title . "</td>";
      echo "<td>" .$description . "</td>";
      echo "<td>" .$starttime . "</td>";
      echo "<td>" .$endtime . "</td>";
      echo "<td>" .$locname . "</td>";
      echo "<td>" .$zipcode . "</td>";
      echo "</tr>";
	  }
	  mysqli_stmt_close($stmt);
}
?>

Sign up for event:<select name = "eventid">
<?php
for($i = 0; $i < $index; $i++){
	echo "<option value = ".$events[$i].">".$events[$i]."</option>";
}
?>
</select>
<input type="submit" value="Sign Up">
</form>

</body>
</html>
