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
 //echo "Connected successfully";

if(isset($_POST["friendRequest"])) {
  $friendRequest = get_post($conn, "friendRequest");
  $query = "SELECT count(*) FROM member Where username = ?";
  if($stmt = mysqli_prepare($conn, $query)){
	 mysqli_stmt_bind_param($stmt, "s", $friendRequest);
	 mysqli_stmt_execute($stmt);
	 mysqli_stmt_bind_result($stmt, $res);
	 mysqli_stmt_fetch($stmt);
	 mysqli_stmt_close($stmt);
   if ($friendRequest == $_SESSION['username']) {
     echo "Can't add yourself.";
   }
	 else if($res == 0){
		 echo "User does not exist";
	 } else {
     addFriend($friendRequest);
   }
 }
}



function addFriend($friendRequest) {
  $query = "INSERT INTO friend (friend_of, friend_to) VALUES(?,?)";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "ss", $friendRequest, $_SESSION['username']);
  if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    echo "You are now friends with $friendRequest";
  } else {
    //echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
    echo "He is already your friend";
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
