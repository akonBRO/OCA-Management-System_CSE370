<?php
session_start(); // Start the session to access session variables

// Destroy all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Optionally, regenerate session ID (for added security)
session_regenerate_id();

// Redirect to the login page after logout
header("Location: index.html");
exit(); // Stop further code execution
?>
