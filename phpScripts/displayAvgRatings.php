<?php
//check for session
session_start();
if(isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    //echo "Welcome $user!";
}
else {
  header("Location: /index.php");
}

$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 //echo "Connected successfully";
 if(isset($_POST["starttime"]) && isset($_POST["endtime"])){
   $starttime = get_post($conn, "starttime");
   $endtime = get_post($conn, "endtime");
   $sql = "SELECT sign_up.event_id, title, description, start_time, end_time, location_name, zipcode, avg(rating)
   FROM belongs_to NATURAL JOIN organize NATURAL JOIN an_event ,sign_up
   WHERE belongs_to.username = '$user' AND sign_up.event_id = an_event.event_id AND start_time > '$starttime' AND end_time < '$endtime'
   GROUP BY sign_up.event_id";
 } else {
   $sql = "SELECT sign_up.event_id, title, description, start_time, end_time, location_name, zipcode, avg(rating)
   FROM belongs_to NATURAL JOIN organize NATURAL JOIN an_event ,sign_up
   WHERE belongs_to.username = '$user' AND sign_up.event_id = an_event.event_id GROUP BY sign_up.event_id";
 }



$result = $conn->query($sql);

 if ($result->num_rows > 0) {
    // output data of each row

    echo "<table border = 1>";
    echo "<tr>";
    echo "<td> Event ID </td>";
    echo "<td> Title </td>";
    echo "<td> Description </td>";
    echo "<td> Start Time</td>";
    echo "<td> End Time </td>";
    echo "<td> Location Name </td>";
    echo "<td> Zipcode </td>";
    echo "<td> Average Rating </td>";
    echo "</tr>";

    while($row = $result->fetch_assoc()) {
        $date = explode(" ", $row["start_time"]);
        if (checkTime($date[0], date('Y-m-d'), -3)) {
            echo "<tr>";
            echo "<td>" . $row["event_id"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["start_time"] . "</td>";
            echo "<td>" . $row["end_time"] . "</td>";
            echo "<td>" . $row["location_name"] . "</td>";
            echo "<td>" . $row["zipcode"] . "</td>";
            echo "<td>" . $row["avg(rating)"] . "</td>";
            echo "</tr>";
        }
    }

} else {
    echo "No Upcoming Events";
}

function checkTime($time, $current, $diffInDays) { //Compares current time and input time with the difference in days
  $secondsDiff = $diffInDays * 24 * 60 * 60;
  if ($diffInDays > 0) {
    if (strtotime($current) - strtotime($time) > $secondsDiff) {
      return FALSE;
    } else if (strtotime($current) - strtotime($time) > 0) {
      return FALSE;
    } else {return TRUE;}
  } else {
    if (strtotime($time) - strtotime($current) < $secondsDiff) {
      return FALSE;
    } else if ((strtotime($time) - strtotime($current) > 0)) {
      return FALSE;
    } else {return TRUE;}
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



$conn->close();



?>
