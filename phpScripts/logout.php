<?php
session_start();
if(isset($_SESSION['username'])) {
    session_start();
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
    
    header("Location: /index.php");
    die();
}
else {
    header("Location: /home.php");
    die();
}
?>