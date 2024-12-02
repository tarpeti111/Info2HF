<?php
require_once "header.php";
require_once "db.php";
require_once "sql_error_handler.php";
require_once "validate_input.php";

// Check user access
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])) {
    $_SESSION['message'] = "Moderator or Admin access required!";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
    exit();
}

// Initialize variables
$missionTitle = validateInput($_POST['missionTitle'] ?? "", 'string', 45);
$description = validateInput($_POST["description"] ?? "", 'string', 200);
$start_date = validateInput($_POST["start_date"] ?? "", 'date');
$end_date = validateInput($_POST['end_date'] ?? "", 'date');
$status = validateInput($_POST['status'] ?? "", 'string');
$launch_location = validateInput($_POST['launch_location'] ?? "", 'string', 200);
$destination = validateInput($_POST['destination'] ?? "", 'string', 200);
$ships = array_filter($_POST['ships'] ?? [], function ($ship) {
    return validateInput($ship, 'int');
});
$missionId = validateInput($_GET['id'] ?? null, 'int');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Required fields validation
    if (empty($missionTitle)) {
        $_SESSION['message'] = "Mission title is required!";
        header("Location: missions_update.php" . ($missionId ? "?id=" . $missionId : ""));
        exit();
    }

    // Validate title length
    if (strlen($missionTitle) > 45) {
        $_SESSION['message'] = "Mission title cannot exceed 45 characters.";
        header("Location: missions_update.php" . ($missionId ? "?id=" . $missionId : ""));
        exit();
    }

    if (empty($start_date)) {
        $_SESSION['message'] = "Start date is required and must be valid!";
        header("Location: missions_update.php" . ($missionId ? "?id=" . $missionId : ""));
        exit();
    }

    if (!empty($end_date) && strtotime($end_date) < strtotime($start_date)) {
        $_SESSION['message'] = "End date cannot be earlier than the start date!";
        header("Location: missions_update.php" . ($missionId ? "?id=" . $missionId : ""));
        exit();
    }

    try {
        $isUpdate = isset($missionId);

        // Insert or Update Query
        $query = $isUpdate 
            ? "UPDATE missions 
               SET title = :title, description = :description, start_date = :start_date, 
                   end_date = :end_date, status = :status, launch_location = :launch_location, 
                   destination = :destination 
               WHERE id = :id"
            : "INSERT INTO missions 
               (title, description, start_date, end_date, status, launch_location, destination) 
               VALUES (:title, :description, :start_date, :end_date, :status, :launch_location, :destination)";
        
        $missionUpdate = $db->prepare($query);
        $missionUpdate->bindParam(":title", $missionTitle, PDO::PARAM_STR);
        $missionUpdate->bindParam(':description', $description, PDO::PARAM_STR);
        $missionUpdate->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $missionUpdate->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $missionUpdate->bindParam(':status', $status, PDO::PARAM_STR);
        $missionUpdate->bindParam(':launch_location', $launch_location, PDO::PARAM_STR);
        $missionUpdate->bindParam(':destination', $destination, PDO::PARAM_STR);
        if ($isUpdate) {
            $missionUpdate->bindParam(':id', $missionId, PDO::PARAM_INT);
        }
        $missionUpdate->execute();

        // Retrieve mission ID if newly inserted
        $missionId = $missionId ?? $db->lastInsertId();

        // Update spaceship assignments
        if ($missionId > 0) {
            $query = "UPDATE spaceships SET missions_id = NULL WHERE missions_id = :id";
            $statement = $db->prepare($query);
            $statement->bindParam(":id", $missionId, PDO::PARAM_INT);
            $statement->execute();

            foreach ($ships as $ship) {
                $shipsUpdate = $db->prepare('UPDATE spaceships SET missions_id = :missionId WHERE id = :shipId');
                $shipsUpdate->bindParam(':missionId', $missionId, PDO::PARAM_INT);
                $shipsUpdate->bindParam(':shipId', $ship, PDO::PARAM_INT);
                $shipsUpdate->execute();
            }
        }

        $_SESSION['message'] = $isUpdate ? "Mission updated successfully!" : "Mission created successfully!";
    } catch (PDOException $e) {
        $_SESSION['message'] = getErrorMessage($e);
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.php"));
        exit();
    }

    header("Location: missions.php");
    exit();
}

// Fetch mission details for editing
if ($missionId) {
    $query = $db->prepare("SELECT * FROM missions WHERE id = :id");
    $query->bindParam(":id", $missionId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        $missionTitle = $result->title ?? "";
        $description = $result->description ?? "";
        $start_date = $result->start_date ?? "";
        $end_date = $result->end_date ?? "";
        $status = $result->status ?? "";
        $launch_location = $result->launch_location ?? "";
        $destination = $result->destination ?? "";

        // Fetch associated ships
        $shipsQuery = $db->prepare("SELECT id FROM spaceships WHERE missions_id = :missions_id");
        $shipsQuery->bindParam(":missions_id", $missionId, PDO::PARAM_INT);
        $shipsQuery->execute();
        $ships = $shipsQuery->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Get mission status options
$query = $db->prepare("SHOW COLUMNS FROM missions LIKE 'status'");
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

$statusOptions = [];
if ($result) {
    preg_match("/^enum\((.*)\)$/", $result['Type'], $matches);
    $statusOptions = isset($matches[1]) ? str_getcsv($matches[1], ',', "'") : [];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/<?= htmlspecialchars($_SESSION["theme"], ENT_QUOTES, 'UTF-8') ?>_theme.css">
    <title>Mission Update</title>
    <script src="../js/add_remove_select.js"></script>
</head>
<body>
    <?php include "alert_message.php"; ?>
    <?php include "navbar.php"; ?>
    <form method="post" class="form-update">
        <div class="form-element-div">
            <label>Msission Title:</label>
            <input type="text" name="missionTitle" value="<?= htmlspecialchars($missionTitle) ?>" required>
        </div>
        <div class="form-element-div">
            <label>Msission Status:</label>
            <select name="status" id="status">
            <?php foreach ($statusOptions as $option): ?>
                <option value="<?= $option ?>" <?= ($option == $status) ? 'selected' : '' ?> required><?= ucfirst($option) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="form-element-div">
            <label>Start Date:</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" required>
        </div>
        <div class="form-element-div">
            <label>End Date:</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
        </div>
        <div class="form-element-div">
            <label>Launch Location:</label>
            <input type="text" name="launch_location" value="<?= htmlspecialchars($launch_location) ?>">
        </div>
        <div class="form-element-div">
            <label>Destination:</label>
            <input type="text" name="destination" value="<?= htmlspecialchars($destination) ?>">
        </div>
        <div class="form-element-div">
            <label>Spaceships:</label>
            <div id="add_selects_here"></div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    loadShips().then(() => {
                        <?php foreach ($ships as $ship): ?>
                        add_select("<?= $ship['id'] ?>");
                        <?php endforeach; ?>
                    });
                });
            </script>
            <button class="button" onclick="add_select()" type="button">Add</button>
        </div>
        <div class="form-element-div">
            <label>Description:</label>
            <textarea id="description" name="description" rows="6" cols="60"><?= htmlspecialchars($description) ?></textarea>
        </div>
        <button class="button" type="submit">Submit</button>
    </form>
</body>
</html>