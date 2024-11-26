<?php
require_once("db.php");
// Get the ships
$shipsQuery = $db->prepare("SELECT name FROM spaceships");
$shipsQuery->execute();
$ships = $shipsQuery->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($ships);