<?php
// Start the session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the owner login page
header("Location: owner-login.html");
exit();
?>
