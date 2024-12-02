<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION["theme"])){
    $_SESSION['theme'] = 'light';
}
?>
<div style="margin-top: 100px;"></div>