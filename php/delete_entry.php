<?php
if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        die("Invalid ID.");
    }

    $allowedTables = ['spaceships', 'astronauts', 'missions', 'users'];
    $table = null;
    require_once("db.php");
    switch (basename($_SERVER['HTTP_REFERER'])) {
        case 'spaceships_view.php':
            $statement = $db->prepare("UPDATE astronauts SET spaceships_id = NULL WHERE spaceships_id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $table = "spaceships";
            break;
        case 'astronauts.php':
            $table = "astronauts";
            break;
        case 'missions.php':
            $statement = $db->prepare("UPDATE spaceships SET missions_id = NULL WHERE missions_id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $table = "missions";
            break;
        case "users":
            if(isset($_SESSION['user']) && $_SESSION['user']['access_level'] === 'admin'){
                $table = "users";
            }
            break;
        default:
            die("Invalid referer.");
    }

    if (in_array($table, $allowedTables)) {
        
        $query = "DELETE FROM `$table` WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $_SESSION['message'] = "Deleted entry, succesfully!";
    } else {
        die("Invalid table.");
    }
}
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $referer");