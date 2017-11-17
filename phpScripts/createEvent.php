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
 echo "Connected successfully";

if(isset($_POST["groupid"]) && isset($_POST["eventname"]) && isset($_POST["description"]) && isset($_POST["starttime"]) && isset($_POST["endtime"]) && isset($_POST["location"])){
  $groupid = get_post($conn, "groupid");
  $eventname = get_post($conn, "eventname");
  $description = get_post($conn, "description");
  $starttime = get_post($conn, "starttime");
  $endtime = get_post($conn, "endtime");
	$location = get_post($conn, "location");
	echo $location;
  list($locname, $zipcode) = explode(":", $location);
  createEvent($groupid, $eventname, $description, $starttime, $endtime, $locname, $zipcode);
} 

function createEvent($groupid, $eventname, $description, $starttime, $endtime, $locname, $zipcode) {
  $query = "INSERT INTO an_event (title,description,start_time,end_time,location_name,zipcode) VALUES(?,?,?,?,?,?)";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "sssssi", $eventname, $description, $starttime, $endtime, $locname, $zipcode);
  if (mysqli_stmt_execute($stmt)) {
      echo "New group created successfully";
      mysqli_stmt_close($stmt);
	  addOrganize($groupid, $eventname, $description, $starttime, $endtime, $locname, $zipcode);
      header("Location: /index.php");
      die();
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
      mysqli_stmt_close($stmt);
    }
}
function addOrganize($groupid, $eventname, $description, $starttime, $endtime, $locname, $zipcode){
	//Find event_id
	$query = "Select event_id From an_event Where title=? AND description=? AND start_time=? AND end_time=? AND location_name=? AND zipcode=?";
	$stmt = mysqli_prepare($GLOBALS["conn"], $query);
	mysqli_stmt_bind_param($stmt, "sssssi", $eventname, $description, $starttime, $endtime, $locname, $zipcode);
	if (mysqli_stmt_execute($stmt)) {
		mysqli_stmt_bind_result($stmt, $eventid);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	else {
		echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
		mysqli_stmt_close($stmt);
	}
	$query = "INSERT INTO organize (event_id, group_id) VALUES(?,?)";
	$stmt2 = mysqli_prepare($GLOBALS["conn"], $query);
	mysqli_stmt_bind_param($stmt2, "ii", $eventid, $groupid);
	if (mysqli_stmt_execute($stmt2)) {
		mysqli_stmt_close($stmt2);
	} else {
		echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
		mysqli_stmt_close($stmt);
    }
	signUp($eventid);
}

function signUp($eventid){
	$query = "INSERT INTO sign_up (event_id, username, rating) VALUES (?,?,-1)";
	$stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "ss",$eventid, $_SESSION["username"]);
  if(mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
	}
	else{
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
