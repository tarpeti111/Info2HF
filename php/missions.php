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
    <title>Missions</title>
    <script src="../js/sort_table.js" defer></script>
</head>
<body>
    <canvas></canvas>
    <?php require_once "alert_message.php"; ?>
    <?php include "navbar.php"; ?>
    <div class="topbar">Missions
        <div style="margin-left: 3%;"></div>
        <div class="search-input">
            <input type="text" id="searchInput" placeholder="Search">
        </div>
        <div class="right">
            <a href="missions_update.php" class="button">Add new Mission</a>
        </div>
    </div>
    <table>
        <tr>
            <th>
                <div class="th-content">
                    <div>Title</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Start Date</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>End Date</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(2, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(2, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Status</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(3, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(3, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Launch Location</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(4, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(4, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Destination</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(5, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(5, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Number of<br>Astronauts</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(6, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(6, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Number of<br>Ships</div>
                    <div class="button-container">
                        <img class="button" onclick="sortTable(7, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick="sortTable(7, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])): ?>
                <th>Update</th>
                <th>Delete</th>
            <?php endif; ?>
        </tr>

        <?php
        // Query to fetch missions along with astronaut and spaceship counts
        $query = $db->query("
            SELECT 
                missions.id AS mission_id, 
                missions.title, 
                missions.start_date, 
                missions.end_date, 
                missions.status, 
                missions.launch_location, 
                missions.destination,
                COUNT(DISTINCT astronauts.id) AS astronaut_count,
                COUNT(DISTINCT spaceships.id) AS spaceship_count
            FROM missions
            LEFT JOIN spaceships ON missions.id = spaceships.missions_id
            LEFT JOIN astronauts ON spaceships.id = astronauts.spaceships_id
            GROUP BY missions.id
        ");

        // Fetch and display each mission row
        while ($row = $query->fetchObject()): ?>
            <tr>
                <td><?= htmlspecialchars($row->title) ?? "" ?></td>
                <td><?= htmlspecialchars($row->start_date) ?? ""  ?></td>
                <td><?= ($row->end_date == "0000-00-00") ? "" : htmlspecialchars($row->end_date) ?></td>
                <td><?= ucfirst(htmlspecialchars($row->status)) ?? "" ?></td>
                <td><?= ucfirst(htmlspecialchars($row->launch_location)) ?? "" ?></td>
                <td><?= ucfirst(htmlspecialchars($row->destination)) ?? "" ?></td>
                <td><?= $row->astronaut_count ?></td>
                <td><?= $row->spaceship_count ?></td>
                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['access_level'], ['moderator', 'admin'])): ?>
                    <td class="td-button">
                        <a class="button-table button-update" href="missions_update.php?id=<?= $row->mission_id ?>">Update</a>
                    </td>
                    <td class="td-button">
                        <a class="button-table button-delete" href="delete_entry.php?id=<?= $row->mission_id ?>">Delete</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>
    <script type="module" src="../js/three.js"></script>
    <script src="../js/search_table.js"></script>
</body>
</html>