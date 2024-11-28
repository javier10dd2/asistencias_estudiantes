<?php 
session_start();
session_unset();
session_destroy();

// Redirect to the login page located in the 'views' folder
header("Location: views/login.php");
exit();
?>
