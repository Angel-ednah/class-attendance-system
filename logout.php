<?php
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to login page
header("Location: login.php");
exit();
