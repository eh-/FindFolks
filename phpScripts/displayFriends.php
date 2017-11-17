<?php
//check for session


$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 //echo "Connected successfully";
 $user = $_SESSION['username'];

 $sql = "SELECT friend_to FROM friend WHERE friend_of = '$user'";
 $result = $conn->query($sql);

 if ($result) {
    if ($result->num_rows > 0) {
    // output data of each row

    echo "<table border = 1>";
    echo "<tr>";
    echo "<td> Your Friends </td>";
    echo "</tr>";

    while($row = $result->fetch_assoc()) {
        {

            echo "<tr>";
            echo "<td> <form action='./phpScripts/displayEvents.php' method='post'>
                <input type='submit' name = Friend value=" . $row["friend_to"] . ">" .
              "</form> </td>";
            echo "</tr>";
        }
    }
  } else {
      echo "No Friends";
  }
}
$conn->close();



?>
