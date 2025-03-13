<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optionally, clear the session cookie

// Redirect to home page
header("Location: index.php");
exit();
?>
