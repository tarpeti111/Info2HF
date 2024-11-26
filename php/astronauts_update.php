<?php
    require_once "header.php";
    require_once "db.php";

    if(!isset($_SESSION['user']))
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    $first_name = "";
    $last_name = "";
    $occupation = "";
    $birth_date = "";
    $shipName = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['occupation']) && isset( $_POST['first_name']) && isset( $_POST['last_name']) && isset($_POST['birth_date'])) {

            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $occupation = $_POST['occupation'];
            $birth_date = $_POST['birth_date'];
            $shipId = null;
            if(!empty($_POST['spaceship'])){
                $spaceship_query = $db->prepare("SELECT id FROM spaceships WHERE name = :name");
                $spaceship_query->bindParam(":name", $_POST['spaceship'], PDO::PARAM_STR);
                $spaceship_query->execute();
                $shipId = $spaceship_query->fetchObject()->id;
            }
            if(isset($_GET['id'])){
                $statement = 'UPDATE astronauts SET first_name = :first_name, last_name = :last_name, occupation = :occupation, birth_date = :birth_date, spaceships_id = :shipId WHERE id = :id';
                $astronautsUpdate = $db->prepare($statement);
                $astronautsUpdate->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
                $astronautsUpdate->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(':birth_date', $birth_date, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(':occupation', $occupation, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(":shipId", $shipId, PDO::PARAM_INT);
                $astronautsUpdate->execute();
            }
            else{
                $statement = 'INSERT INTO astronauts (first_name, last_name, occupation, birth_date';
                if(isset($shipId)){
                    $statement .= ", spaceships_id";
                }
                $statement .= ") VALUES (:first_name, :last_name, :occupation, :birth_date";
                if(isset($shipId)){
                    $statement .= ", :shipId";
                }
                $statement .= ")";
                $astronautsUpdate = $db->prepare($statement);
                $astronautsUpdate->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(':birth_date', $birth_date, PDO::PARAM_STR);
                $astronautsUpdate->bindParam(':occupation', $occupation, PDO::PARAM_STR);
                if(isset($shipId))
                {
                    $astronautsUpdate->bindParam(":shipId", $shipId, PDO::PARAM_INT);
                }
                $astronautsUpdate->execute();
            }
        }
        header("Location: astronauts.php");
        exit();
    }

    if(isset($_GET["id"])){
        $id = $_GET["id"];

        $query = $db->prepare("SELECT * FROM astronauts WHERE :id = id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        if ($result = $query->fetchObject()) {
            $first_name = $result->first_name;
            $last_name = $result->last_name;
            $occupation = $result->occupation;
            $birth_date = $result->birth_date;
            $shipId = null;
            
            if(isset($result->spaceships_id)){
                $shipQuery = $db->prepare("SELECT name FROM spaceships WHERE id = :spaceships_id");
                $shipQuery->bindParam(":spaceships_id", $result->spaceships_id, PDO::PARAM_INT);
                $shipQuery->execute();
                $shipName = $shipQuery->fetchObject()->name;
            }
        }
    }

// Query to get column details
$query = $db->prepare("SHOW COLUMNS FROM astronauts LIKE 'occupation'");
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
$query = $db->prepare("SELECT name FROM spaceships");
$query->execute();

$spaceshipOptions = [];
$spaceshipOptions[] = "";
$results = $query->fetchAll(PDO::FETCH_ASSOC);
if ($results) {
    foreach ($results as $result) {
        $spaceshipOptions[] = $result['name'];
    }
}
?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require "style.php"; ?>
        <title>Astronaut Form</title>
        <script src="../js/add_remove_select.js"></script>
    </head>
    <body>
        <?php include "alert_message.php" ?>
        <?php include "navbar.php"?>
        <form method="post" class="form-update">
            <input type="text" name="first_name" value="<?= $first_name?>">
            <input type="text" name="last_name" value="<?= $last_name?>">
            <select name="occupation" id="occupation">
            <?php foreach($typeOptions as $option):?>
                <option
                    value="<?= $option ?>"
                    <?php if($option == $occupation):?>selected<?php endif; ?>
                    ><?= ucfirst($option) ?>
                </option>
            <?php endforeach; ?>
            </select><br>
            <input type="date" value="<?= $birth_date ?>" name="birth_date">
            <select name="spaceship" id="spaceship">
            <?php foreach($spaceshipOptions as $option):?>
                <option
                    value="<?= $option ?>"
                    <?php if($option == $shipName):?>selected<?php endif; ?>
                    ><?= ucfirst($option) ?>
                </option>
            <?php endforeach; ?>
            </select><br>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>