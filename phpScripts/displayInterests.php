<?php
//check for session


$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 //echo "Connected successfully";

 $sql = "SELECT category,keyword FROM interest";
 $result = $conn->query($sql);


 if ($result->num_rows > 0) {
    // output data of each row
    echo "<form action='./phpScripts/displayGroups.php' method='post'>";
    echo "<select name='Interest'>";
    while($row = $result->fetch_assoc()) {
          echo "<option>" . $row["category"] . ": " . $row["keyword"] . "</option>";
    }
    echo "</select>";

    echo "<input type='submit' value='Check Groups'>";
    echo "</form>";
} else {
    echo "No Interests";
}
$conn->close();



?>
