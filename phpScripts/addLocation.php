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

if(isset($_POST["locname"]) && isset($_POST["zipcode"]) && isset($_POST["address"]) && isset($_POST["description"]) && isset($_POST["lat"]) && isset($_POST["long"])){
  $locname = get_post($conn, "locname");
	$zipcode = get_post($conn, "zipcode");
	$address = get_post($conn, "address");
	$description = get_post($conn, "description");
	$lat = get_post($conn, "lat");
	$long = get_post($conn, "long");
  addLocation($locname, $zipcode, $address, $description, $lat, $long);
	
} 

function addLocation($locname, $zipcode, $address, $description, $lat, $long){
	$query = "Select count(*) from location where location_name = ? AND zipcode = ?";
	$stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "ss", $locname, $zipcode);
  if (mysqli_stmt_execute($stmt)) {
		mysqli_stmt_bind_result($stmt, $count);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		if($count == 0){
			$query = "INSERT INTO location (location_name, zipcode, address, description, latitude, longitude) VALUES (?,?,?,?,?,?)";
			$stmt = mysqli_prepare($GLOBALS["conn"], $query);
			mysqli_stmt_bind_param($stmt, "sissii", $locname, $zipcode, $address, $description, $lat, $long);
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_close($stmt);
				header("Location: /createEvent.php");
				die();
			} 
			else {
				echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
				mysqli_stmt_close($stmt);
			}
		}
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
