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
  $query = "SELECT event_id, title, description, start_time, end_time, location_name, zipcode FROM an_event NATURAL JOIN sign_up WHERE username='$user'";
  $result = $conn->query($query);

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
      $dateSplit = explode("-", $date[0]);
      $dayDifference = $dateSplit[2] - date('d');
      if ($dateSplit[0] == date('Y') && $dateSplit[1] == date('m')
          && ($dayDifference <= 3) && ($dayDifference >= 0)) {
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
  
?>