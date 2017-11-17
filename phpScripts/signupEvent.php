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

if(isset($_POST["eventid"])){
  $eventid = get_post($conn, "eventid");
  signUp($eventid);
} 

function signUp($eventid){
	$query = "INSERT INTO sign_up (event_id, username, rating) VALUES (?,?,-1)";
	$stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "ss",$eventid, $_SESSION["username"]);
  if(mysqli_stmt_execute($stmt)) {
		echo "New group created successfully";
    mysqli_stmt_close($stmt);
    header("Location: /index.php");
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