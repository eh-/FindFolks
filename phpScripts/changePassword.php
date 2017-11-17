<?php

//check for session
session_start();
if(isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
  header("Location: /index.php");
  die();
}

// Create connection
$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
$conn = mysqli_connect($hn, $un, $pw, $db);

 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 //echo "Connected successfully";

if(isset($_POST["oldPassword"]) && isset($_POST["newPassword"]) &&
   isset($_POST["conPassword"])) {

    $oldPass = get_post($conn, "oldPassword");
    $newPass =  get_post($conn, "newPassword");
    $conPass = get_post($conn, "conPassword");
}

if (checkInput($newPass) == 1) {
  $pass = hash('ripemd128', $oldPass); //hash the password
  $query = "SELECT password FROM member WHERE username = '$user'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
     // output data of each row
     $row = $result->fetch_assoc();
     if ($pass != $row["password"]) {
       echo "Wrong Password";
     } else if ($newPass != $conPass) {
       echo "Passwords do not match";
     } else {
       $newPass = hash('ripemd128', $newPass);
       $query = "UPDATE member SET password = ? WHERE username ='$user'";
       $stmt = mysqli_prepare($GLOBALS["conn"], $query);
       mysqli_stmt_bind_param($stmt, "s", $newPass);
       if (mysqli_stmt_execute($stmt)) {
           echo "Password Updated";
           mysqli_stmt_close($stmt);
       } else {
           echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
           mysqli_stmt_close($stmt);
         }
     }
 } else {
     echo "No such user";
 }
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

function checkInput($newPass) {
    echo"<br>";
    if(strlen($newPass) > 32) {
        return "Password is too long";
    } else {
        return 1;
    }
}

$conn->close();
?>
<br>
<a href="/home.php">Home</a>
