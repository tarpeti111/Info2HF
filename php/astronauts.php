<?php
require_once "header.php";
require_once "db.php";

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
    <script type="importmap"><?php include "../json/importmap.json"; ?></script>
    <title>Space Mission Manager</title>
    <script src="../js/sort_table.js" defer></script>
</head>
<body>
    <canvas></canvas>
    <?php require_once "alert_message.php"; ?>
    <?php include "navbar.php"; ?>
    <div class="topbar">Astronauts
        <div style="margin-left: 3%;"></div>
        <div class="search-input">
            <input type="text" id="searchInput" placeholder="Search">
        </div>
        <div class="right">
            <a href="astronauts_update.php" class="button">Add new Astronaut</a>
        </div>
    </div>
    <table>
        <tr>
            <th>
                <div class="th-content">
                    <div>Name</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Occupation</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Ship Name</div>
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
            <th>
                <div class="th-content">
                    <div>Birth Date</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(4, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(4, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])): ?>
            <th>Update</th>
            <th>Delete</th>
            <?php endif; ?>
        </tr>
        <?php
        $query = $db->query("
            SELECT 
                astronauts.id AS astronaut_id, 
                astronauts.first_name, 
                astronauts.last_name, 
                astronauts.occupation, 
                astronauts.birth_date, 
                spaceships.name AS spaceship_name, 
                missions.title AS mission_title
            FROM astronauts
            LEFT JOIN spaceships ON astronauts.spaceships_id = spaceships.id
            LEFT JOIN missions ON spaceships.missions_id = missions.id
        ");

        while ($row = $query->fetchObject()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row->first_name) ?> <?= htmlspecialchars($row->last_name) ?></td>
            <td><?= htmlspecialchars(ucfirst($row->occupation)) ?></td>
            <td><?= htmlspecialchars($row->spaceship_name ?? "") ?></td>
            <td><?= htmlspecialchars($row->mission_title ?? "") ?></td>
            <td><?= htmlspecialchars($row->birth_date) ?></td>
            <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])): ?>
            <td class="td-button">
                <a class="button-update" href="astronauts_update.php?id=<?= $row->astronaut_id ?>">Update</a>
            </td>
            <td class="td-button">
                <a class="button-delete" href="delete_entry.php?id=<?= $row->astronaut_id ?>">Delete</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endwhile; ?>
    </table>
    <script type="module" src="../js/three.js"></script>
    <script src="../js/search_table.js"></script>
</body>
</html>