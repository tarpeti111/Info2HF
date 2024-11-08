<?php
function getDb($hostname, $dbname, $username, $password){
    try {
        $db = new PDO("mysql:host=$hostname; dbname=$dbname", "$username", "$password");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        printf('Sikertelen kapcsolódás: ' . $e->getMessage());
        exit;
    }
}