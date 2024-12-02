<?php
require_once("header.php");

// Unset the user session to log out
unset($_SESSION['user']);

header("Location: " . "index.php");
exit();