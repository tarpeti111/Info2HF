<?php
    require_once "header.php";
    require_once "db.php";
    require_once "sql_error_handler.php";

    if(!isset($_SESSION['user']) || $_SESSION['user']['access_level'] != "admin")
    {
        $_SESSION['message'] = "Admin access required!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $missionTitle = $_POST['missionTitle'] ?? "";
    $description = $_POST["description"] ?? "";
    $start_date = $_POST["start_date"] ?? "";
    $end_date = $_POST['end_date'] ?? "";
    $status = $_POST['status'] ?? "";
    $launch_location = $_POST['launch_location'] ?? "";
    $destination = $_POST['destination'] ?? "";
    $image_url = $_POST['image_url'] ?? "";
    $ships = $_POST['ships'] ?? [];
    $missionId = $_GET['id'] ?? -1;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(empty($missionTitle) || empty($start_date)) {
            $_SESSION['message'] = "One of the required fields was left empty";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit();
        }
        else{
            $update = false;
            $missionUpdate = null;
            try {
                //code...
                if (!isset($_GET['id'])) {
                    $query = "INSERT INTO missions 
                        (title, description, start_date, end_date, status, launch_location, destination, image_url)
                        VALUES (:title, :description, :start_date, :end_date, :status, :launch_location, :destination, :image_url)";
                } else {
                    $update = true;
                    $query = "UPDATE missions SET 
                        title = :title, description = :description, start_date = :start_date, 
                        end_date = :end_date, status = :status, launch_location = :launch_location, 
                        destination = :destination, image_url = :image_url WHERE id = :id";
                }
                if(!empty($query)) {
                    $missionUpdate = $db->prepare($query);
                    $missionUpdate->bindParam(":title", $missionTitle, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':description', $description, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':start_date', $start_date, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':end_date', $end_date, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':status', $status, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':launch_location', $launch_location, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':destination', $destination, PDO::PARAM_STR);
                    $missionUpdate->bindParam(':image_url', $image_url, PDO::PARAM_STR);
                    if($update) {
                        $missionUpdate->bindParam(':id', $missionId, PDO::PARAM_INT);
                    }
                    $missionUpdate->execute();

                    if(!$update) {
                        $missionId = $db->lastInsertId();
                    }
                }
                if ($missionId > -1) {
                    $query = "UPDATE spaceships SET missions_id = NULL WHERE missions_id = :id";
                    $statement = $db->prepare($query);
                    $statement->bindParam(":id", $missionId, PDO::PARAM_INT);
                    $statement->execute();

                    if(!empty($ships)){
                        foreach($ships as $ship) {
                            $name = $ship;
                            $shipsUpdate = $db->prepare('UPDATE spaceships SET missions_id = :missionId WHERE name = :name');
                            $shipsUpdate->bindParam(':missionId', $missionId, PDO::PARAM_INT);
                            $shipsUpdate->bindParam(':name', $name, PDO::PARAM_STR);
                            $shipsUpdate->execute();
                        }
                    
                    }
                }
                $_SESSION['message'] = "Inserted succesfully";
            } catch (PDOException $e) {
                $_SESSION['message'] = getErrorMessage($e);
                header("Location: " . ($_SERVER['HTTP_REFERER']) ?? "index.php");
                exit();
            }
        }
        header("Location: missions.php");
        exit();
        //}
    }

    if($missionId > 0){

        $query = $db->prepare("SELECT * FROM missions WHERE id = :id");
        $query->bindParam(":id", $missionId, PDO::PARAM_INT);
        $query->execute();
        if ($result = $query->fetchObject()) {
            $missionTitle = $result->title ?? "";
            $description = $result->description ?? "";
            $start_date = $result->start_date ?? "";
            $end_date = $result->end_date ?? "";
            $status = $result->status ?? "";
            $launch_location = $result->launch_location ?? "";
            $destination = $result->destination ?? "";
            $image_url = $result->image_url ?? "";
            $ships = $_GET['ships'] ?? [];
            // Get the crew members
            $shipsQuery = $db->prepare("SELECT name FROM spaceships WHERE missions_id = :missions_id");
            $shipsQuery->bindParam(":missions_id", $missionId, PDO::PARAM_INT);
            $shipsQuery->execute();
            $ships = $shipsQuery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

$query = $db->prepare("SHOW COLUMNS FROM missions LIKE 'status'");
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);
if ($result) {
    preg_match("/^enum\((.*)\)$/", $result['Type'], $matches);
    $statusOptions = isset($matches[1]) ? str_getcsv($matches[1], ',', "'") : [];
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
            <input type="text" name="missionTitle" value="<?= $missionTitle ?>">

            <select name="status" id="status">
            <?php foreach($statusOptions as $option):?>
                <option
                    value="<?= $option ?>"
                    <?php if($option == $status):?>selected<?php endif; ?>
                    ><?= ucfirst($option) ?>
                </option>
            <?php endforeach; ?>
            </select><br>

            <input type="date" name="start_date" value="<?= $start_date ?>">
            <input type="date" name="end_date" value="<?= $end_date ?>">
            <input type="text" name="launch_location" value="<?= $launch_location ?>">
            <input type="text" name="destination" value="<?= $destination ?>">

            <div id="add_selects_here"></div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    loadShips().then(() => {
                        <?php foreach ($ships as $ship): ?>
                        add_select("<?= $ship['name'] ?>");
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