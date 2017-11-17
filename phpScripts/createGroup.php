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

if(isset($_POST["groupname"]) && isset($_POST["description"]) && isset($_POST["category"]) && isset($_POST["keyword"])) {
  $groupname = get_post($conn, "groupname");
  $description = get_post($conn, "description");
  $category = get_post($conn, "category");
  $keyword = get_post($conn, "keyword");
  $query = "SELECT count(*) FROM interest Where category = ? AND keyword = ?";
  if($stmt =  mysqli_prepare($conn, $query)){
	 mysqli_stmt_bind_param($stmt, "ss", $category, $keyword);
	 mysqli_stmt_execute($stmt);
	 mysqli_stmt_bind_result($stmt, $res);
	 mysqli_stmt_fetch($stmt);
	 mysqli_stmt_close($stmt);
	 if($res == 0){
		 createInterest($category, $keyword);
	 }
  createGroup($groupname, $description, $category, $keyword);
  }
}
function createInterest($category, $keyword){
  $query = "INSERT INTO interest (category,keyword) VALUES(?,?)";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "ss", $category, $keyword);
  if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_close($stmt);
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
      mysqli_stmt_close($stmt);
  } 
}
function createGroup($groupname, $description, $category, $keyword) {
  $query = "INSERT INTO a_group (group_name,description,creator) VALUES(?,?,?)";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "sss", $groupname, $description, $_SESSION["username"]);
  if (mysqli_stmt_execute($stmt)) {
      echo "New group created successfully";
      mysqli_stmt_close($stmt);
      addUserToBelongsTo($groupname, $description);
	  addAbout($groupname, $description, $category, $keyword);
      header("Location: /index.php");
      die();
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
      mysqli_stmt_close($stmt);
  }  
}
function addUserToBelongsTo($groupname, $description) {
  $user = $_SESSION["username"];
  $auth = 1;
  //get group_id 
  $query = "SELECT group_id FROM a_group WHERE creator=? AND group_name=? AND description=?";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "sss", $user, $groupname, $description);
  if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_bind_result($stmt, $group_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
  }
  else {
    echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
    mysqli_stmt_close($stmt);
  }
  //add creator to belongs_to
  $query = "INSERT INTO belongs_to (group_id,username,authorized) VALUES(?,?,?)";   
  $stmt2 = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt2, "ssi", $group_id, $user, $auth);
  if (mysqli_stmt_execute($stmt2)) {
    mysqli_stmt_close($stmt2);
  }
  else {
    echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
    mysqli_stmt_close($stmt);
  }
}
function addAbout($groupname, $description, $category, $keyword){
  $user = $_SESSION["username"];
  $query = "SELECT group_id FROM a_group WHERE creator=? AND group_name=? AND description=?";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "sss", $user, $groupname, $description);
  if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_bind_result($stmt, $group_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
  }
  else {
    echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
    mysqli_stmt_close($stmt);
  }
  $query = "INSERT INTO about (category,keyword,group_id) VALUES(?,?,?)";   
  $stmt2 = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt2, "ssi", $category, $keyword, $group_id);
  if (mysqli_stmt_execute($stmt2)) {
    mysqli_stmt_close($stmt2);
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