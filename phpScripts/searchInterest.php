<?php
//check for session
session_start();
if(isset($_SESSION['username'])) {}
else {
    header("Location: /index.php");
    die();
}

$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }

if(isset($_POST["searchInterest"]) ){
  list($category, $keyword) = explode(":", get_post($conn, "searchInterest"));
	displayEvents($category, $keyword);
} 

function displayEvents($category, $keyword) {
	echo "<h1>Upcoming Events with Same Interests </h1>";
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
  $query = "SELECT event_id, title, description, start_time, end_time, location_name, zipcode FROM an_event NATURAL JOIN organize Natural Join about WHERE category = ? And keyword = ?";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "ss", $category, $keyword);
  if (mysqli_stmt_execute($stmt)) {
		mysqli_stmt_bind_result($stmt, $eventid, $title, $description, $starttime, $endtime, $locname, $zipcode);
	  while(mysqli_stmt_fetch($stmt)){
			$date = explode(" ", $starttime);
      $dateSplit = explode("-", $date[0]);
      $dayDifference = $dateSplit[2] - date('d');
      if ($dateSplit[0] == date('Y') && $dateSplit[1] == date('m') && ($dayDifference <= 3) && ($dayDifference >= 0)) {
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
		}
    mysqli_stmt_close($stmt);
    die();
	} 
	else {
      echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
      mysqli_stmt_close($stmt);
  }
}

function get_post($conn, $var) {
    $var = $conn->real_escape_string($_POST[$var]);
    $var = sanitizeInput($var);
    return $var;
}

function sanitizeInput($s) {
    $s = stripslashes($s);
    $s = strip_tags($s);
    $s = htmlentities($s);
    return $s;
}
?>
