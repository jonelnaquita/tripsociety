<?php 
session_start();

// Destroy all session data
session_unset(); // Free all session variables
session_destroy(); // Destroy the session

// Redirect to the home page
header("Location: home.php");
exit(); // Ensure no further code is executed
?>