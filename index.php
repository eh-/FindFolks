<?php
session_start();
if(isset($_SESSION['username'])) {
    header("Location: /home.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<body>


<form action="./phpScripts/login.php" method="post">
  Username: <input type="text" name="username" pattern="[A-Za-z0-9]{3,}" required><br>
  Password: <input type="password" name="password" pattern="[A-Za-z0-9]{3,}" required><br>
  <input type="submit" value="Login">
</form>

<form action="./register.php" method="post">
  <input type="submit" value="Register">
</form>

<?php
  echo "<h3> Select Interests to see Groups </h3>";
  include './phpScripts/displayInterests.php';
  include './phpScripts/displayEvents.php';
?>

<?php
  echo "<h3> Events for the Next Three Days </h3>";
?>

  
</body>
</html>
