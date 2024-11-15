<?php
    unset($_SESSION['user']);
    header("Location: " . $_SERVER['HTTP_REFERER']);