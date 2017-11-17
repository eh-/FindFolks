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
  <table border="1">
    <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Location</th>
      <th>Zipcode</th>
    </tr>
    
<?php
  $hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
  $conn = mysqli_connect($hn, $un, $pw, $db);
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
    
  if(isset($_POST["groupid"]) && isset($_POST["groupName"]) && 
     isset($_POST["description"])) {
  $groupid = get_post($conn, "groupid");
  $groupName = get_post($conn, "groupName");
  $description = get_post($conn, "description");
  echo "Events for " . $groupName . "<br><br>";
  }

  $query = "SELECT event_id, title, an_event.description, start_time, end_time, location_name, 
  zipcode FROM an_event JOIN organize USING (event_id) JOIN a_group USING (group_id) 
  WHERE group_id=? AND group_name=? AND a_group.description=?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "iss", $groupid, $groupName, $description);
  if(mysqli_stmt_execute($stmt)) {
    mysqli_stmt_bind_result($stmt, $eventid, $title, $description, $start_time, $end_time,
    $location_name, $zipcode);
    while(mysqli_stmt_fetch($stmt)) {  
      echo "<tr>" . "<td>" . $title . "</td>"
                  . "<td>" . $description . "</td>"
                  . "<td>" . $start_time . "</td>"
                  . "<td>" . $end_time . "</td>"
                  . "<td>" . $location_name . "</td>"
                  . "<td>" . $zipcode . "</td>"
          ."</tr>";
    }
    mysqli_stmt_close($stmt);
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
  mysqli_close($conn);
?>
</table>
  <a href="/home.php">Home</a>
</body>
</html>