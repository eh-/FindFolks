<?php

//check for session
session_start();
if(isset($_SESSION['username'])) {
    header("Location: /home.php");
    die();
}

// Create connection
$hn = "localhost"; $un = "root"; $pw = "Raidegre"; $db = "FindFolks";
$conn = mysqli_connect($hn, $un, $pw, $db);

 // Check connection
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 echo "Connected successfully";

if(isset($_POST["username"]) && isset($_POST["password"]) &&
   isset($_POST["firstname"]) && isset($_POST["lastname"]) &&
   isset($_POST["email"]) && isset($_POST["zipcode"])) {
    
    $user = get_post($conn, "username");
    $pass =  get_post($conn, "password");
    $firstname = get_post($conn, "firstname");
    $lastname = get_post($conn, "lastname");
    $email = get_post($conn, "email");
    $zipcode = get_post($conn, "zipcode");
}

if (checkInput($user, $pass, $firstname, $lastname, $email, $zipcode) == 1) {
  $pass = hash('ripemd128', $pass); //hash the password
  $query = "INSERT INTO member (username,password,firstname,lastname,email,zipcode)
            VALUES(?,?,?,?,?,?)";
  $stmt = mysqli_prepare($GLOBALS["conn"], $query);
  mysqli_stmt_bind_param($stmt, "sssssi", $user, $pass, $firstname, $lastname, $email, $zipcode);
  if (mysqli_stmt_execute($stmt)) {
      echo "New record created successfully";
      mysqli_stmt_close($stmt);
      header("Location: /index.php");
      die();
  } else {
      echo "Error: " . $sql . "<br>" . $GLOBALS["conn"]->error;
      mysqli_stmt_close($stmt);
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

function checkInput($user, $pass, $firstname, $lastname, $email, $zipcode) {
    echo"<br>";
    if(strlen($user) > 20 || strlen($pass) > 32) 
        return "username or password is too long";
    else if(strlen($firstname) > 20 || strlen($lastname) > 20) 
        return "first name or last name is too long";
    else if(strlen($email) > 32) 
        return "email is too long";
    else if(strlen($zipcode) > 5)
        return "zipcode is too long";
    else if(strlen($user) < 3 || strlen($pass) < 3 || strlen($firstname) < 3 || strlen($lastname) < 3 ||
            strlen($email) < 3 || strlen($zipcode) < 5)
        return "Fields must be greater than 3 characters";
    else
        return 1;
}

 // $sql = "INSERT INTO member (username,password,firstname,lastname,email,zipcode)
 // VALUES ('$_POST['username']', '$_POST['password']', '$_POST['firstname']', '$_POST['lastname']',' $_POST['email']', '$_POST['zipcode']')";

$conn->close();
?>
