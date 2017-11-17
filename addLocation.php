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

<form action="./phpScripts/addLocation.php" method="post">
  Location Name: <input type="text" name="locname" pattern="{3,20}" required><br>
	Zipcode:<input type="number" name="zipcode" min="9999" max="99999" required><br>
	Address:<input type="text" name="address" pattern="{3,50}" required><br>
  Description: <textarea name="description" cols="50" rows="3" pattern="{3,100}" required></textarea><br>
	Latitude: <input type="number" name="lat" min="-90" max="90" required><br>
	Longitude: <input type="number" name="long" min="-180" max="180" required><br>

  <input type="submit" value="Add Location">
</form>

</body>
</html>
