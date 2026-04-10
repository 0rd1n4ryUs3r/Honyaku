<?php

include 'config.php';

session_start();
session_unset();
session_destroy();

// Prevent browser caching after logout
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header('location:login.php');

?>