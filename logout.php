<?php
// This script logs the user out by destroying the session and then redirects to the login page.
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>