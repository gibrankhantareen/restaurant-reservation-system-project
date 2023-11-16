<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

echo "<script>alert('Logged Out Successfully!'); window.location.href='index.html';</script>";

exit;
?>
