<?php
require_once "header.php";
require_once "db.php";
require_once "sql_error_handler.php";
require_once "validate_input.php";

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])) {
    $_SESSION['message'] = "Moderator or Admin access Required!";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
    exit();
}

// Get all occupations to be used in a select and validation process
$query = $db->prepare("SHOW COLUMNS FROM astronauts LIKE 'occupation'");
$query->execute();
$typeOptions = [];
$result = $query->fetch(PDO::FETCH_ASSOC);
if ($result && strpos($result['Type'], 'enum') === 0) {
    $enum = $result['Type'];
    $enum = substr($enum, 5, -1);
    $typeOptions = str_getcsv($enum, ',', "'");
}

$first_name = validateInput($_POST['first_name'] ?? "", 'string', 45);
$last_name = validateInput($_POST['last_name'] ?? "", 'string', 45);
$occupation = validateInput($_POST['occupation'] ?? "", 'string');
$birth_date = validateInput($_POST['birth_date'] ?? "", 'date');
$shipId = validateInput($_POST['spaceship'] ?? null, 'int');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required inputs
    if (empty($first_name) || empty($last_name) || empty($occupation) || empty($birth_date)) {
        $_SESSION['message'] = "All fields are required!";
        header("Location: astronauts_update.php" . (isset($_GET['id']) ? "?id=" . $_GET['id'] : ""));
        exit();
    }

    // Validate occupation
    if (!in_array($occupation, $typeOptions, true)) {
        $_SESSION['message'] = "Invalid occupation selected.";
        header("Location: astronauts_update.php" . (isset($_GET['id']) ? "?id=" . $_GET['id'] : ""));
        exit();
    }

    // Validate birth_date
    if (!$birth_date || $birth_date > date('Y-m-d')) {
        $_SESSION['message'] = "The birth date must be valid and cannot be in the future.";
        header("Location: astronauts_update.php" . (isset($_GET['id']) ? "?id=" . $_GET['id'] : ""));
        exit();
    }

    // Validate spaceship ID if provided
    if ($shipId !== null && !array_key_exists($shipId, $spaceshipOptions)) {
        $_SESSION['message'] = "Invalid spaceship selected.";
        header("Location: astronauts_update.php" . (isset($_GET['id']) ? "?id=" . $_GET['id'] : ""));
        exit();
    }

    try {
        $isUpdate = isset($_GET['id']) && validateInput($_GET['id'], 'int');

        // Set query based on the operation (insert or update)
        $query = $isUpdate
            ? "UPDATE astronauts 
               SET first_name = :first_name, last_name = :last_name, occupation = :occupation, birth_date = :birth_date, spaceships_id = :shipId 
               WHERE id = :id"
            : "INSERT INTO astronauts (first_name, last_name, occupation, birth_date, spaceships_id) 
               VALUES (:first_name, :last_name, :occupation, :birth_date, :shipId)";

        // Bind parameters and execute the query
        $stmt = $db->prepare($query);
        if ($isUpdate) {
            $stmt->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
        }
        $stmt->bindParam(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $last_name, PDO::PARAM_STR);
        $stmt->bindParam(":occupation", $occupation, PDO::PARAM_STR);
        $stmt->bindParam(":birth_date", $birth_date, PDO::PARAM_STR);
        $stmt->bindParam(":shipId", $shipId, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['message'] = ($isUpdate ? "Updated" : "Inserted") . " successfully!";
        header("Location: astronauts.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = getErrorMessage($e);
        header("Location: astronauts_update.php" . (isset($_GET['id']) ? "?id=" . $_GET['id'] : ""));
        exit();
    }
}

// Load astronaut data for editing if ID is provided
if (isset($_GET["id"]) && validateInput($_GET["id"], 'int')) {
    $id = $_GET["id"];
    $stmt = $db->prepare("SELECT * FROM astronauts WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($result = $stmt->fetchObject()) {
        $first_name = $result->first_name;
        $last_name = $result->last_name;
        $occupation = $result->occupation;
        $birth_date = $result->birth_date;
        $shipId = $result->spaceships_id;
    }
}

// Get all spaceships for selection options
$query = $db->prepare("SELECT id, name FROM spaceships");
$query->execute();
$spaceshipOptions = [];
foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
    $spaceshipOptions[$result['id']] = $result['name'];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/<?=$_SESSION["theme"]?>_theme.css">
    <title>Astronaut Form</title>
    <script src="../js/add_remove_select.js"></script>
</head>
<body>
    <?php include "alert_message.php" ?>
    <?php include "navbar.php"?>

    <form method="post" class="form-update">
        <div class="form-element-div">
            <label>First Name:</label>
            <input type="text" name="first_name" placeholder="First Name" value="<?= htmlspecialchars($first_name) ?>" required>
        </div>
        <div class="form-element-div">
            <label>Last Name:</label>
            <input type="text" name="last_name" placeholder="Last Name" value="<?= htmlspecialchars($last_name) ?>" required>
        </div>
        <div class="form-element-div">
            <label>Occupation:</label>
            <select name="occupation" id="occupation">
                <?php foreach($typeOptions as $option): ?>
                    <option value="<?= htmlspecialchars($option) ?>" <?= ($option == $occupation) ? 'selected' : '' ?> required>
                        <?= ucfirst(htmlspecialchars($option)) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
        </div>
        <div class="form-element-div">
            <label>Birth Date:</label>
            <input type="date" value="<?= htmlspecialchars($birth_date) ?>" name="birth_date" required>
        </div>
        <div class="form-element-div">
            <label>Spaceship:</label>
            <select name="spaceship" id="spaceship">
                <option value="">No Ship</option>
                <?php foreach($spaceshipOptions as $id => $name): ?>
                    <option value="<?= htmlspecialchars($id) ?>" <?= ($id == $shipId) ? 'selected' : '' ?>>
                        <?= ucfirst(htmlspecialchars($name)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="button" type="submit">Submit</button>
    </form>
</body>
</html>