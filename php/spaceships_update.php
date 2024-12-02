<?php
require_once "header.php";
require_once "db.php";
require_once "sql_error_handler.php";
require_once "validate_input.php";

// Check for moderator or admin access
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])) {
    $_SESSION['message'] = "Moderator or Admin access required!";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
    exit();
}

// Initialize variables
$name = validateInput($_POST['name'] ?? "", 'string', 45);
$type = validateInput($_POST['type'] ?? "", 'string');
$description = validateInput($_POST['description'] ?? "", 'string', 200);
$mission = validateInput($_POST['mission'] ?? null, 'int');
$crew = [];

// Handle empty mission input
$mission = $mission === "" ? null : $mission;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation
    if (empty($name) || empty($type)) {
        $_SESSION['message'] = "Name and Type are required fields!";
        header("Location: spaceships_update.php" . (isset($_GET['id']) ? "?id=" . $_GET['id'] : ""));
        exit();
    }

    try {
        $isUpdate = isset($_GET['id']) && validateInput($_GET['id'], 'int');
        $shipId = $isUpdate ? $_GET['id'] : null;

        // Determine query type
        $query = $isUpdate
            ? "UPDATE spaceships 
               SET name = :name, type = :type, description = :description, missions_id = :missions_id 
               WHERE id = :shipId"
            : "INSERT INTO spaceships (name, type, description, missions_id) 
               VALUES (:name, :type, :description, :missions_id)";

        // Prepare and execute the main query
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':missions_id', $mission, PDO::PARAM_INT);
        if ($isUpdate) {
            $stmt->bindParam(':shipId', $shipId, PDO::PARAM_INT);
        }
        $stmt->execute();

        // Get the ID for the ship (newly inserted or from URL)
        if (!$isUpdate) {
            $shipId = $db->lastInsertId();
        }

        // Update astronauts' spaceship assignments
        $stmt = $db->prepare("UPDATE astronauts SET spaceships_id = NULL WHERE spaceships_id = :shipId");
        $stmt->bindParam(':shipId', $shipId, PDO::PARAM_INT);
        $stmt->execute();

        if (isset($_POST['astronauts']) && is_array($_POST['astronauts'])) {
            foreach ($_POST['astronauts'] as $astronaut) {
                if (validateInput($astronaut, 'int')) {
                    $stmt = $db->prepare("UPDATE astronauts SET spaceships_id = :shipId WHERE id = :astronautId");
                    $stmt->bindParam(':shipId', $shipId, PDO::PARAM_INT);
                    $stmt->bindParam(':astronautId', $astronaut, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }

        $_SESSION['message'] = ($isUpdate ? "Updated" : "Inserted") . " successfully!";
        header("Location: spaceships_view.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = getErrorMessage($e);
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.php"));
        exit();
    }
}

// If editing an existing spaceship, load its data
if (isset($_GET['id']) && validateInput($_GET['id'], 'int')) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM spaceships WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($result = $stmt->fetchObject()) {
        $name = $result->name;
        $type = $result->type;
        $description = $result->description;
        $mission = $result->missions_id;

        // Load crew members assigned to this spaceship
        $stmt = $db->prepare("SELECT id FROM astronauts WHERE spaceships_id = :shipId");
        $stmt->bindParam(':shipId', $result->id, PDO::PARAM_INT);
        $stmt->execute();
        $crew = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Load options for spaceship types
$stmt = $db->prepare("SHOW COLUMNS FROM spaceships LIKE 'type'");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$typeOptions = [];
if ($result && strpos($result['Type'], 'enum') === 0) {
    $enum = $result['Type'];
    $enum = substr($enum, 5, -1);
    $typeOptions = str_getcsv($enum, ',', "'");
}

// Load options for missions
$stmt = $db->prepare("SELECT id, title FROM missions");
$stmt->execute();
$missionOptions = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $result) {
    $missionOptions[$result['id']] = $result['title'];
}
?>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/<?=$_SESSION["theme"]?>_theme.css">
        <title>Spaceship Form</title>
        <script src="../js/add_remove_select.js"></script>
    </head>
    <body>
        <?php include "alert_message.php" ?>
        <?php include "navbar.php"?>
        <form method="post" class="form-update">
            <div class="form-element-div">
                <label>Ship Name:</label>
                <input type="text" name="name" value="<?= $name ?>" required>
            </div>
            <div class="form-element-div">
                <label>Type:</label>
                <select name="type" id="type" required>
                <?php foreach($typeOptions as $option):?>
                    <option
                        value="<?= $option ?>"
                        <?php if($option == $type):?>selected<?php endif; ?>
                        ><?= ucfirst($option) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-element-div">
            <label>Mission:</label>
                <select name="mission" id="mission">
                    <option value="">No Mission</option>
                <?php foreach($missionOptions as $id => $title):?>
                <option
                    value="<?= $id ?>"
                    <?php if($id == $mission):?>selected<?php endif; ?>
                    ><?= ucfirst($title) ?>
                </option>
            <?php endforeach; ?>
            </select>
            </div>
            <div class="form-element-div">
                <label>Astronauts:</label>        
                <div id="add_selects_here"></div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        loadAstronauts().then(() => {
                            <?php foreach ($crew as $crewMember): ?>
                            add_select("<?= $crewMember['id'] ?>");
                            <?php endforeach; ?>
                        });
                    });
                </script>
                <button class="button" onclick="add_select()" type="button">Add</button>
            </div>
            <div class="form-element-div">
            <label>Description:</label>
                <textarea id="description" name="description" rows="6" cols="60"><?= $description ?></textarea>
            </div>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>