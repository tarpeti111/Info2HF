<?php
session_start();  // Ensure the session is started

// Check if the user is logged in and has appropriate access
if (!isset($_SESSION["user"]) || !in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])) {
    $_SESSION['message'] = "Moderator or Admin access required!";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
    exit();
}

// Check if the 'id' parameter is provided
if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    // If ID is invalid, return with an error message
    if (!$id) {
        $_SESSION['message'] = "Unexpected error, invalid ID provided.";
        header("Location: index.php");
        exit();
    }

    // Define the allowed tables for deletion
    $allowedTables = ['spaceships', 'astronauts', 'missions', 'users'];
    $table = null;
    require_once("db.php");  // Ensure DB connection is included

    // Determine which table to delete from based on the referrer URL
    $referer = basename($_SERVER['HTTP_REFERER'] ?? '');
    switch ($referer) {
        case 'spaceships_view.php':
            // Clear the spaceship assignment from astronauts before deleting the spaceship
            $statement = $db->prepare("UPDATE astronauts SET spaceships_id = NULL WHERE spaceships_id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $table = "spaceships";
            break;
        
        case 'astronauts.php':
            $table = "astronauts";
            break;
        
        case 'missions.php':
            // Clear the spaceship assignment for missions before deleting the mission
            $statement = $db->prepare("UPDATE spaceships SET missions_id = NULL WHERE missions_id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $table = "missions";
            break;
        
        case 'users.php':
            // Only admins should be allowed to delete users
            if ($_SESSION['user']['access_level'] === 'admin') {
                $table = "users";
            }
            break;
        
        default:
            // If the referer doesn't match, don't proceed with deletion
            $_SESSION['message'] = "Unexpected error, invalid source page.";
            header("Location: index.php");
            exit();
    }

    // Proceed with deletion if the table is valid
    if (in_array($table, $allowedTables)) {
        // Prepare the DELETE query to remove the entry
        $query = "DELETE FROM `$table` WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        
        // Execute the delete operation
        if ($statement->execute()) {
            $_SESSION['message'] = "Entry successfully deleted!";
        } else {
            $_SESSION['message'] = "Failed to delete the entry, please try again.";
        }
    } else {
        // In case of an invalid table or operation
        $_SESSION['message'] = "Unexpected error, please try again.";
    }
}

// Redirect back to the referring page or to index.php
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $referer");
exit();