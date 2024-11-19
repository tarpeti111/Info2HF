<?php
    require_once "header.php";
    require_once "db.php";

    if(!isset($_SESSION['user']))
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo("posts");
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
$options = [];
$result = $query->fetch(PDO::FETCH_ASSOC);
if ($result && strpos($result['Type'], 'enum') === 0) {
    // Extract the ENUM definition
    $enum = $result['Type']; // e.g., "enum('value1','value2','value3')"
    
    // Remove the "enum(" and ")" parts
    $enum = substr($enum, 5, -1);
    
    // Split the values into an array
    $options = str_getcsv($enum, ',', "'");
}
?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require "style.php"; ?>
        <title>Log In</title>
        <script src="../js/add_remove_select.js"></script>
    </head>
    <body>
        <?php include "navbar.php"?>
        <form method="post" class="form-update">
            <input type="text" value="<?= $name ?>">

            <select name="type" id="type">
            <?php foreach($options as $option):?>
                <option
                    value="<?= $option ?>"
                    <?php if($option == $type):?>selected<?php endif; ?>
                    ><?= ucfirst($option) ?>
                </option>
            <?php endforeach; ?>
            </select><br>

            <div id="add_selects_here"></div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    loadAstronauts().then(() => {
                        <?php foreach ($crew as $crewMember): ?>
                        add_select("<?= $crewMember['first_name'] . " " . $crewMember['last_name'] ?>");
                        <?php endforeach; ?>
                    });
                });
            </script>
            <button class="button" onclick="add_select()" type="button">Add</button>
            <textarea id="description" name="description" rows="6" cols="60"><?= $description ?></textarea>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>