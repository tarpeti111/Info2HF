<?php
    require_once "header.php";
    require_once "db.php";

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
        if($result = $query->fetchObject()){
            $name = $result->name;
            $description = $result->description;
            $type = $result->type;

            $mission = $db->query("SELECT title FROM missions WHERE $result->missions_id = missions.id")->fetchObject()->title;
            $crew[] = $db->query("SELECT first_name, last_name FROM astronauts WHERE astronauts.id = $result->id")->fetchAll(PDO::FETCH_ASSOC);
        }
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
        <?= $name . " " ?><br>
        <?= $description . " " ?><br>
        <?= $type . " " ?><br>
        <?php for( $i = 0; $i < count($crew); $i++ ){
            echo var_dump($crew[$i]);
        }?>
    </body>
</html>