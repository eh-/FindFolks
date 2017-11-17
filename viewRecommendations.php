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
$query = "SELECT username, title, description, start_time, end_time, location_name, zipcode 
          FROM an_event NATURAL JOIN recommend WHERE friend='$user'";
$result = $conn->query($query);
if(!$result){
  echo "No recommendations";
}
else {
  showTable();
}

function showTable() {
echo "My Recommendations<br>";
echo "<table border=1>";
echo "<tr>";
echo "<th>Recommended By</th>";
echo "<th>Title</th>";
echo "<th>Description</th>";
echo "<th>Start Time</th>";
echo "<th>End Time</th>";
echo "<th>Location</th>";
echo "<th>Zipcode</th>";
echo "</tr>";
while($row = $GLOBALS['result']->fetch_assoc()) {
  echo "<tr>";
  echo "<td>" . $row["username"] . "</td>";
  echo "<td>" . $row["title"]. "</td>";
  echo "<td>" . $row["description"]. "</td>";
  echo "<td>" . $row["start_time"]. "</td>";
  echo "<td>" . $row["end_time"]. "</td>";
  echo "<td>" . $row["location_name"]. "</td>";
  echo "<td>" . $row["zipcode"]. "</td>";
  echo "</tr>";
}
echo "</table>";
}
?>