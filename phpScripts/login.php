<?php

//check for session
session_start();
if(isset($_SESSION['username'])) {
    header("Location: /home.php");
    die();
}

$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";

$conn = mysqli_connect($hn, $un, $pw, $db);

// Check connection
if (!$conn) {
    echo "cannot connect to db";
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST["username"]) && isset($_POST["password"])) {
    $user = get_post($conn, "username");
    $pass = get_post($conn, "password");
    login($user, $pass);
}

function login($username,$password) {
  $hashedPW = hash("ripemd128", $password);
  $query = "SELECT password FROM member WHERE username=?";
  if($stmt = mysqli_prepare($GLOBALS["conn"], $query)) {
      mysqli_stmt_bind_param($stmt, "s", $username); //the "s" means string
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $storedPW);
      mysqli_stmt_fetch($stmt);
    
      if($hashedPW == $storedPW) {
        echo "Successfully logged in<br>" . "Redirecting you in 1 second";
        session_start();
        $_SESSION['username'] = $username;
        sleep(1);
        header("Location: /home.php");
        mysqli_stmt_close($stmt);
        die();
      }
      else {
        echo "Invalid username or password";
        mysqli_stmt_close($stmt);
      }
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

$conn->close();
?>
