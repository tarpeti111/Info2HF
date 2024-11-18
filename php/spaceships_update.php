<?php
    require_once "header.php";
    require_once "db.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo("fasz");
    }

    $name = "";
    $crew = [];
    $mission = "";
    $type = "";
    $description = "";

    if(isset($_GET["id"])){
        $id = $_GET["id"];

        $query = $db->prepare("SELECT * FROM spaceships WHERE :id = id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        if ($result = $query->fetchObject()) {
            $name = $result->name;
            $description = $result->description;
            $type = $result->type;
    
            // Get the mission title
            $missionQuery = $db->prepare("SELECT title FROM missions WHERE id = :missions_id");
            $missionQuery->bindParam(":missions_id", $result->missions_id, PDO::PARAM_INT);
            $missionQuery->execute();
            $mission = $missionQuery->fetchObject()->title;
    
            // Get the crew members
            $crewQuery = $db->prepare("SELECT first_name, last_name FROM astronauts WHERE spaceships_id = :spaceships_id");
            $crewQuery->bindParam(":spaceships_id", $result->id, PDO::PARAM_INT);
            $crewQuery->execute();
            $crew = $crewQuery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

// Query to get column details
$query = $db->prepare("SHOW COLUMNS FROM spaceships LIKE 'type'");
$query->execute();

// Fetch the column details
$values = [];
$result = $query->fetch(PDO::FETCH_ASSOC);
if ($result && strpos($result['Type'], 'enum') === 0) {
    // Extract the ENUM definition
    $enum = $result['Type']; // e.g., "enum('value1','value2','value3')"
    
    // Remove the "enum(" and ")" parts
    $enum = substr($enum, 5, -1);
    
    // Split the values into an array
    $values = str_getcsv($enum, ',', "'");
}
?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require "style.php"; ?>
        <title>Log In</title>
    </head>
    <body>
        <form method="post">
            <input type="text" value="<?= $name ?>">
            <select name="type" id="type" value="<?= $type ?>">
            <?php foreach($values as $value):?>
                <option value="<?= $value ?>"><?= $value ?></option>
            <?php endforeach; ?>
            </select>
            <input class="description" type="text" value="<?= $description ?>">
            <button class="button" type="submit">Submit</button>
        </form>
        <?php for( $i = 0; $i < count($crew); $i++ ){
            echo $crew[$i]['first_name'] . " " . $crew[$i]['last_name'] . " ";
        }?>
    </body>
</html>