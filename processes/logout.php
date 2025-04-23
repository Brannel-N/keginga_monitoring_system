<?php
session_start();

// Optionally destroy the entire session
session_destroy();

// Redirect to login page
header("Location: ../login.php");
exit();
