<?php
require_once("header.php");

if($_SESSION["theme"] == "light"){
    $_SESSION["theme"] = "dark";
}
else{
    $_SESSION["theme"] = $_SESSION["light"];
}
    
header("Location: " . $_SERVER['HTTP_REFERER'] ?? "index.php");
exit();