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

<form action="./phpScripts/changePassword.php" method="post">
  Current Password: <input type="password" name="oldPassword" pattern="[A-Za-z0-9]{3,32}" required><br>
	New Password:<input type="password" name="newPassword" pattern="[A-Za-z0-9]{3,32}" required><br>
	Confirm New Password:<input type="password" name="conPassword" pattern="[A-Za-z0-9]{3,32}" required><br>
  <input type="submit" value="Change Password">
</form>

</body>
</html>
