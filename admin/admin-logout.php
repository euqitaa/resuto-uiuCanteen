<?php
// Start session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page with a logout success message
header("Location: login.html?success=loggedout");
exit();
?>
