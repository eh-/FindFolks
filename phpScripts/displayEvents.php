<?php
//check for session


$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 //echo "Connected successfully";
if(isset($_POST["Friend"])) {
  $user = $_POST["Friend"];
  echo $user;
  echo "'s Events";
  echo "<br>";
  $sql = "SELECT event_id, title, description, start_time, end_time, location_name, zipcode FROM an_event NATURAL JOIN sign_up WHERE username = '$user'";
} else {
  $sql = "SELECT event_id, title, description, start_time, end_time, location_name, zipcode FROM an_event";
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
    echo "</tr>";

    while($row = $result->fetch_assoc()) {
        $date = explode(" ", $row["start_time"]);
        if (checkTime($date[0], date('Y-m-d'), 3)) {
            echo "<tr>";
            echo "<td>" . $row["event_id"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["start_time"] . "</td>";
            echo "<td>" . $row["end_time"] . "</td>";
            echo "<td>" . $row["location_name"] . "</td>";
            echo "<td>" . $row["zipcode"] . "</td>";
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



$conn->close();



?>
