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

if(isset($_POST["groups"]) && isset($_POST["userToAuth"])) {
  $groupid = get_post($conn, "groups");
  $userToAuth = get_post($conn, "userToAuth");
  
  $query = "SELECT count(*) FROM member WHERE username = ?";
  $stmt3 = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt3, "s", $userToAuth);
  mysqli_stmt_bind_result($stmt3, $result);
  if(mysqli_stmt_execute($stmt3)) {
    mysqli_stmt_fetch($stmt3);
    mysqli_stmt_close($stmt3);
  }
  else {
    echo "Error: " . $query . "<br>" . $GLOBALS["conn"]->error;
  }
  
  $query = "SELECT group_name FROM a_group WHERE group_id=?";
  $stmt4 = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt4, "s", $groupid);
  mysqli_stmt_bind_result($stmt4, $groupName);
  if(mysqli_stmt_execute($stmt4)) {
    mysqli_stmt_fetch($stmt4);
    mysqli_stmt_close($stmt4);
  }
  else {
    echo "Error: " . $query . "<br>" . $GLOBALS["conn"]->error;
  }
  
  if($groupid == "") {
    echo "group not set <br>";
  }
  
  if ($result == 0) {
    echo "username doesn't exist";
  }
  if($groupid != "" && $result != 0) {
    echo "Authorized " . $userToAuth . " for " .$groupName;
  }
} // end if
$query = "UPDATE belongs_to SET authorized=1 WHERE username=? AND group_id=?";
$stmt2 = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt2, "si", $userToAuth, $groupid);
if(mysqli_stmt_execute($stmt2)) {
  mysqli_stmt_close($stmt2);
}
else {
  echo "Error: " . $query . "<br>" . $GLOBALS["conn"]->error;
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<body>";
echo "<table border='1'>";
echo "<tr>";
echo "<th>Group</th>";
echo "<th>Member</th>";
echo "<th></th>";
echo "</tr>";
echo "<tr>";
echo "<form action='/phpScripts/authMember.php' method='post'>";
echo "<td><select name='groups'>";
echo "<option></option>";
$query = "SELECT group_id, group_name, description FROM a_group WHERE creator = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
if(mysqli_stmt_execute($stmt)) {
  mysqli_stmt_bind_result($stmt, $groupid, $groupName, $description);
  while(mysqli_stmt_fetch($stmt)) {
    echo "<option value='" . $groupid . "'>" . $groupName . "</option>";
    
  }
  mysqli_stmt_close($stmt);
}

echo "</select></td>";
echo "<td> <input type='text' name='userToAuth' value='Username'></td>";
echo "<td>
           <input type='submit' value='Authorize'>
           </form>
      </td>";
echo "</tr>";
echo "</table>";
echo "<a href='/home.php'>Home</a>";
echo "</body>";
echo "</html>";

mysqli_close($conn);

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