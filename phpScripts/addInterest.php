<?php
//Check for session
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

if(isset($_POST["category"]) && isset($_POST["keyword"])){
  $category = get_post($conn, "category");
  $keyword = get_post($conn, "keyword");
  $query = "Select count(*) From interest where category = ? and keyword = ?";
	if($stmt = mysqli_prepare($conn, $query)){
		mysqli_stmt_bind_param($stmt, "ss", $category, $keyword);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $res);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		//If interest isn't in database insert it
		if($res == 0) {
			$query = "INSERT INTO interest (category, keyword) VALUES (?,?)";
			if($stmt = mysqli_prepare($conn, $query)){
				mysqli_stmt_bind_param($stmt, "ss", $category, $keyword);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
		}
	}
	addInterest($category, $keyword);
	header("Location: /index.php");
  die();
}
else {
  echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
  mysqli_stmt_close($stmt);
}

function addInterest($category, $keyword){
	$query = "INSERT INTO interested_in (username, category, keyword) VALUES (?,?,?)";
	if($stmt = mysqli_prepare($GLOBALS["conn"], $query)){
		mysqli_stmt_bind_param($stmt, "sss", $_SESSION["username"], $category, $keyword);
		mysqli_stmt_execute($stmt);
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