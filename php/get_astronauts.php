<?php
require_once("db.php");
// Get the astronauts
$astronautsQuery = $db->prepare("SELECT id, first_name, last_name FROM astronauts");
$astronautsQuery->execute();
$astronauts = $astronautsQuery->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($astronauts);