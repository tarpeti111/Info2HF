<?php
    require_once "header.php";
    require_once "db.php";
    require_once "sql_error_handler.php";

    if(!isset($_SESSION['user']))
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    $name = $_POST['name'] ?? "";
    $crew = [];
    $mission = $_POST['mission'] ?? "";
    $type = $_POST['type'] ?? "";
    $description = $_POST['description'] ?? "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $missionId = null;
        if(empty($name) || empty($type)) {
            $_SESSION['message'] = "One of the required fields was left empty";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit();
        }else{
            $shipId = -1;
            try{
                if(!empty($mission)){
                    $missionQuery = $db->prepare("SELECT id FROM missions WHERE title = :missionTitle");
                    $missionQuery->bindParam(":missionTitle", $mission, PDO::PARAM_STR);
                    $missionQuery->execute();
                    $missionId = $missionQuery->fetchObject()->id;
                }
    
                $shipUpdate = null;
                if (!isset($_GET['id'])) {
                    //INSERT INTO spaceships (name, type, description, missions_id) VALUES (:name, :type, :description, :missions_id 
                    $query = "INSERT INTO spaceships (name, type, description, missions_id) VALUES (:name, :type, :description, :missions_id)";
                    $shipUpdate = $db->prepare($query);
                    $shipUpdate->bindParam(":name", $name, PDO::PARAM_STR);
                    $shipUpdate->bindParam(':description', $description, PDO::PARAM_STR);
                    $shipUpdate->bindParam(':type', $type, PDO::PARAM_STR);
                    $shipUpdate->bindParam(':missions_id', $missionId, PDO::PARAM_INT);
                    $shipUpdate->execute();
                
                    // Retrieve the last inserted ID
                    $shipId = $db->lastInsertId();
                } else {
                    $shipId = $_GET['id'];
                    $shipUpdate = $db->prepare("UPDATE spaceships SET name = :name, description = :description, type = :type, missions_id = :missions_id WHERE id = :shipId");
                    $shipUpdate->bindParam(":shipId", $shipId, PDO::PARAM_INT);
                    $shipUpdate->bindParam(":name", $name, PDO::PARAM_STR);
                    $shipUpdate->bindParam(':description', $description, PDO::PARAM_STR);
                    $shipUpdate->bindParam(':type', $type, PDO::PARAM_STR);
                    $shipUpdate->bindParam(':missions_id', $missionId, PDO::PARAM_INT);
                    $shipUpdate->execute();
                }
                if ($shipId > 0) {
                    $query = "UPDATE astronauts SET spaceships_id = NULL WHERE spaceships_id = :id";
                    $statement = $db->prepare($query);
                    $statement->bindParam(":id", $shipId, PDO::PARAM_INT);
                    $statement->execute();
    
                    if(isset($_POST['astronauts'])) {
                        foreach($_POST['astronauts'] as $astronaut) {
                            $names = explode(" ", $astronaut, 2);
                            $first_name = $names[0];
                            $last_name = $names[1];
        
                            $astronautsUpdate = $db->prepare('UPDATE astronauts SET spaceships_id = :shipId WHERE first_name = :first_name AND last_name = :last_name');
                            $astronautsUpdate->bindParam(':shipId', $shipId, PDO::PARAM_INT);
                            $astronautsUpdate->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                            $astronautsUpdate->bindParam(":last_name", $last_name, PDO::PARAM_STR);
                            $astronautsUpdate->execute();
                        }
                    }
                }
                $_SESSION['message'] = "Inserted succesfully";
            }
            catch (PDOException $e) {
                $_SESSION['message'] = getErrorMessage($e);
                header("Location: " . ($_SERVER['HTTP_REFERER']) ?? "index.php");
                exit();
            }
        }
        header("Location: spaceships_view.php");
        exit();
    }

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
$typeOptions = [];
$result = $query->fetch(PDO::FETCH_ASSOC);
if ($result && strpos($result['Type'], 'enum') === 0) {
    // Extract the ENUM definition
    $enum = $result['Type']; // e.g., "enum('value1','value2','value3')"
    
    // Remove the "enum(" and ")" parts
    $enum = substr($enum, 5, -1);
    
    // Split the values into an array
    $typeOptions = str_getcsv($enum, ',', "'");
}
$query = $db->prepare("SELECT title FROM missions");
$query->execute();

$missionOptions = [];
$missionOptions[] = "";
$results = $query->fetchAll(PDO::FETCH_ASSOC);
if ($results) {
    foreach ($results as $result) {
        $missionOptions[] = $result['title'];
    }
}
?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require "style.php"; ?>
        <title>Spaceship Form</title>
        <script src="../js/add_remove_select.js"></script>
    </head>
    <body>
        <?php include "alert_message.php" ?>
        <?php include "navbar.php"?>
        <form method="post" class="form-update">
            <input type="text" name="name" value="<?= $name ?>">

            <select name="type" id="type">
            <?php foreach($typeOptions as $option):?>
                <option
                    value="<?= $option ?>"
                    <?php if($option == $type):?>selected<?php endif; ?>
                    ><?= ucfirst($option) ?>
                </option>
            <?php endforeach; ?>
            </select><br>

            <select name="mission" id="mission">
            <?php foreach($missionOptions as $option):?>
                <option
                    value="<?= $option ?>"
                    <?php if($option == $mission):?>selected<?php endif; ?>
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