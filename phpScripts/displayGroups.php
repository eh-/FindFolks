<?php
//check for session


$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);

 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 //echo "Connected successfully";

if(isset($_POST["Interest"])) {
  $interest = get_post($conn, "Interest");
  $interestSplit = explode(":", $interest);
  $category = $interestSplit[0];
  $keyword =  substr($interestSplit[1], 1, strlen($interestSplit[1])); //Need to strip the beginning space
} else {
  echo "Not set";
  header("Location: /displayInterests.php");
}

 $sql = "SELECT group_id FROM about WHERE category='$category' AND keyword='$keyword'";
 $result = $conn->query($sql);
 if ($result->num_rows > 0) {
    // output data of each row
    echo "<table border = 1>";
    echo "<tr>";
    echo "<td> Group ID </td>";
    echo "<td> Group Name </td>";
    echo "<td> Creator </td>";
    echo "<td> Description </td>";
    echo "</tr>";
    while($row = $result->fetch_assoc()) {
          
          $group_id = $row["group_id"];
          $groupSQL = "SELECT * FROM a_group WHERE group_id='$group_id'";
          $groups = $conn->query($groupSQL);
          if ($groups->num_rows > 0) {
             // output data of each row
             while($group = $groups->fetch_assoc()) {
               echo "<td>" . $group["group_id"] . "</td>";
               echo "<td>" . $group["group_name"] . "</td>";
               echo "<td>" . $group["creator"] . "</td>";
               echo "<td>" . $group["description"] . "</td>";
             }
          }
    }
} else {
    echo "No Groups with such interests";
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
