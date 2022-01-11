<!--ID : 2050093-BSE -->
<!--Name : Mak Hon Sang -->
<!--Topic : Logout Page-->
<?php
session_start();

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy();

header("Location:index.php?msg=logout");

?>