<?php
session_start();
session_destroy(); // End the user session
header("Location: login.php"); // Redirect back to login page
exit();
?>