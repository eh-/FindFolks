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
      <th>Group Name</th>
      <th>Description</th>
      <th></th>
    </tr>
    
<?php
$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
$conn = mysqli_connect($hn, $un, $pw, $db);
 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }

$query = "SELECT group_id, group_name, description FROM a_group WHERE creator = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
if(mysqli_stmt_execute($stmt)) {
  mysqli_stmt_bind_result($stmt, $groupid, $groupName, $description);
  while(mysqli_stmt_fetch($stmt)) {
    echo "<tr>" . "<td>" . $groupName . "</td>"
                . "<td>" . $description . "</td>"
                . "<td>"
                . "<form action='/phpScripts/viewGroupEvents.php' method='post'>"
                . "<input name='groupid' value='" . $groupid . "'hidden></input>"
                . "<input name='groupName' value='" . $groupName . "'hidden></input>" 
                . "<input name='description' value='" . $description . "'hidden></input>" 
                . "<input type='submit' value='View Events'>"
                . "</form>"
                . "</td>"
        ."</tr>";
  }
  mysqli_stmt_close($stmt);
}
  mysqli_close($conn);
?>
</table>
  <a href="/phpScripts/authMember.php">Authorize a member for a group</a>
  <br>
  <a href="/home.php">Home</a>
</body>
</html>