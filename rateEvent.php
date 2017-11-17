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
      <th>Event</th>
      <th>Description</th>
      <th>Rating</th>
    </tr>
    
<?php
$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }

  //rate event
  if(isset($_POST["rating"]) && isset($_POST["eventid"])) {
    $rating = get_post($conn, "rating");
    $eventid = get_post($conn, "eventid");
    if($rating < 0)
      $rating = 0;
  }
  $query = "UPDATE sign_up SET rating = ? WHERE event_id = ? AND username = ?";
  $stmt2 = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt2, "iss", $rating, $eventid, $_SESSION["username"]);
  if(mysqli_stmt_execute($stmt2)) {
    mysqli_stmt_close($stmt2);
  }
  else {
    echo "Error: " . $query . "<br>" . $GLOBALS["conn"]->error;
    mysqli_stmt_close($stmts);
  } 
  //display event name, description, and current rating  
  $query = "SELECT event_id, title, description, rating, end_time 
            FROM an_event NATURAL JOIN sign_up WHERE username = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
  if(mysqli_stmt_execute($stmt)) {
    mysqli_stmt_bind_result($stmt, $eventid, $title, $description, $rating, $endtime);
    while(mysqli_stmt_fetch($stmt)) {
      //if(strtotime($endtime) < strtotime(date("Y-m-d H:i:s"))) {
      if(strtotime($endtime) < strtotime("now")) {
      echo "<tr>";
      echo "<td>" . $title . "</td>";
      echo "<td>" . $description . "</td>";
      echo "<td>"
         . "<form action='rateEvent.php' method='post'>"
         . "<input type='text' name='eventid' value=" . $eventid . " hidden> </input>"
         . "<select name='rating'>"
         . "<option value = $rating>" . $rating . "</option>";
      for($i = 0; $i < 6; $i++) {
        if($i != $rating) {
          echo "<option value = $i>" . $i . "</option>";
        }
      }
      echo "</select>"
         . "<input type='submit' value='Rate'>"
         . "</form>"
         . "</td>";
      echo "</tr>";
      } //end check date
    } //end while
    mysqli_stmt_close($stmt);
  }
  else {
      echo "Error: " . $query . "<br>" . $GLOBALS["conn"]->error;
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
?>
  </table>
  <a href="/home.php">Home</a>
</body>
</html>