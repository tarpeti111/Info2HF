<?php 
    require_once("header.php");
    require_once("db.php");

    if (!isset($_SESSION['user'])) {
        $_SESSION['message'] = "Log In Required!";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
        exit();
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/<?=$_SESSION["theme"]?>_theme.css">
    <title>Spaceships</title>
    <script type="importmap"><?php include "../json/importmap.json"; ?></script>
    <script src="../js/sort_table.js" defer></script>
</head>
<body>
    <?php require_once "alert_message.php"; ?>
    <?php include "navbar.php"; ?>
    <div class="topbar">Space Ships
        <div style="margin-left: 3%;"></div>
        <div class="search-input">
            <input type="text" id="searchInput" placeholder="Search">
        </div>
        <div class="right">
            <a href="spaceships_update.php" class="button">Add new Spaceship</a>
        </div>
    </div>
    <table>
        <tr>
            <th>
                <div class="th-content">
                    <div>Ship Name</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Type</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Crew</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(2, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(2, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Mission</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(3, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(3, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])): ?>
            <th>Update</th>
            <th>Delete</th>
            <?php endif; ?>
        </tr>
        <?php
        // Query with JOIN
        $query = $db->query("
            SELECT 
                spaceships.id AS spaceship_id, 
                spaceships.name AS spaceship_name, 
                spaceships.type, 
                spaceships.missions_id, 
                astronauts.first_name AS crew_first_name, 
                astronauts.last_name AS crew_last_name
            FROM spaceships
            LEFT JOIN astronauts ON spaceships.id = astronauts.spaceships_id
            LEFT JOIN missions ON spaceships.missions_id = missions.id
        ");

        // Fetch the data
        $spaceships = [];
        while ($row = $query->fetchObject()) {
            $spaceships[$row->spaceship_id]['name'] = $row->spaceship_name;
            $spaceships[$row->spaceship_id]['type'] = $row->type;
            $spaceships[$row->spaceship_id]['mission'] = $row->missions_id;
            $spaceships[$row->spaceship_id]['crew'][] = $row->crew_first_name . ' ' . $row->crew_last_name;
        }

        // Output the rows
        foreach ($spaceships as $spaceshipId => $spaceshipData):
            $missionTitle = $spaceshipData['mission'] ? $db->query("SELECT title FROM missions WHERE id = {$spaceshipData['mission']} LIMIT 1")->fetchObject()->title : "No mission";
        ?>
        <tr>
            <td><?= htmlspecialchars($spaceshipData['name']) ?></td>
            <td><?= htmlspecialchars(ucfirst($spaceshipData['type'])) ?></td>
            <td>
                <?= implode('<br>', $spaceshipData['crew']) ?>
            </td>
            <td>
                <?= htmlspecialchars($missionTitle) ?>
            </td>
            <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])): ?>
            <td class="td-button">
                <a class="button-table button-update" href="spaceships_update.php?id=<?= $spaceshipId ?>">Update</a>
            </td>
            <td class="td-button">
                <a class="button-table button-delete" href="delete_entry.php?id=<?= $spaceshipId ?>">Delete</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
    <canvas></canvas>
    <script type="module" src="../js/three.js"></script>
    <script src="../js/search_table.js"></script>
</body>
</html>