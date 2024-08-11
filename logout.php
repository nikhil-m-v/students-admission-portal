<?php
session_start();
session_destroy(); // Destroy the session
header("Location: user.php"); // Redirect to login page
exit();
