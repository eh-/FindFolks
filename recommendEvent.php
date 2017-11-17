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
$user = $_SESSION["username"];

if(isset($_POST["event_id"]) && isset($_POST["friend_of"])) {
  $eventid = $_POST["event_id"];
  $query = "SELECT title FROM an_event WHERE event_id='$eventid'";
  $result = $GLOBALS['conn']->query($query);
  $row = $result->fetch_assoc();
  recommend($_POST["event_id"], $_POST["friend_of"]);
  echo "Recommended " . $_POST["friend_of"] . " to event: " . $row["title"];
}
echo "<!DOCTYPE html>";
echo "<html>";
echo "<body>";
echo "<table border='1'>";
echo "<tr>";
echo "<th>Event</th>";
echo "<th>Friend</th>";
echo "<th></th>";
echo "</tr>";
echo "<tr>";
echo "<form action='/recommendEvent.php' method='post'>";
echo "<td><select name='event_id'>";
echo "<option></option>";

$query = "SELECT event_id, title, start_time FROM an_event NATURAL JOIN sign_up WHERE 
          username='$user'";
$result = $conn->query($query);
while($row = $result->fetch_assoc()) {
  if(strtotime("now") < strtotime($row["start_time"]))
    echo "<option value='" . $row["event_id"] . "'>" . $row["title"] . "</option>";
}
echo "</select></td>";

echo "<td><select name='friend_of'>";
echo "<option></option>";
$query = "SELECT friend_of FROM friend WHERE friend_to='$user'";
$result = $conn->query($query);
while($row = $result->fetch_assoc()) {
  echo "<option value='" . $row["friend_of"] . "'>" . $row["friend_of"] . "</option>";
}
echo "</select></td>";
echo "<td>
      <input type='submit' value='Recommend'>
      </form>
      </td>";
echo "</tr>";
echo "</table>";
echo "<a href='/home.php'>Home</a>";
echo "</body>";
echo "</html>";

function recommend($event_id, $friendToInvite) {
  $user = $GLOBALS['user'];
  $query = "INSERT INTO recommend VALUES (?,?,?)";
  $stmt = mysqli_prepare($GLOBALS['conn'], $query);
  mysqli_stmt_bind_param($stmt, "ssi", $user, $friendToInvite, $event_id);
  if(mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
  }
  else {
    //echo "Error: " . $query . "<br>" . $GLOBALS["conn"]->error;
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