<?php
session_start(); // Start session

// Destroy the session to log the user out
session_unset(); // Clear session data
session_destroy(); // Destroy the session

// Redirect to login page after logging out
header("Location: login.php");
exit();
?>
