<?php
//check for session
session_start();
if(isset($_SESSION['username'])) {
    header("Location: /home.php");
    die();
}
?>

<!DOCTYPE html>
<html>
<body>

<form action="./phpScripts/register.php" method="post">
  Please only use letters and numbers<br><br>
  Username: <input type="text" name="username" pattern="[A-Za-z0-9]{3,20}" required><br>
  Password: <input type="password" name="password" pattern="[A-Za-z0-9]{3,32}" required><br>
  First Name: <input type="text" name="firstname" pattern="[A-Za-z0-9]{3,20}" required><br>
  Last Name: <input type="text" name="lastname" pattern="[A-Za-z0-9]{3,20}" required><br>
  Email: <input type="email" name="email" pattern="[A-Za-z0-9@.]{9,32}" required><br>
  Zipcode: <input type="number" name="zipcode" min="10000" max="99999" maxlength="5" required><br>
  <input type="submit" value="Register">
</form>

</body>
</html>
