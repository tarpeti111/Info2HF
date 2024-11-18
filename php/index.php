<?php
require_once "header.php";
require_once "db.php";
$username = $_SESSION['user']["username"] ?? ""; 
?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include("style.php")?>
        <title>Home</title>
        <script src="../js/sort_table.js" defer></script>
        <script type="importmap"><?php include "../json/importmap.json"; ?></script>
    </head>
    <body class="<?= $_SESSION["theme"] ?>">
        <canvas></canvas>
        <?php include "navbar.php" ?>
        <div class="topbar">Welcome <?= $username ?></div>
        <div class="home-content">
            <div>
                <h1>
                    Navigation:
                </h1>
                <a class="button" href="astronauts.php">Astronauts</a><br><br>
                <a class="button" href="spaceships.php">SpaceShips</a><br><br>
                <a class="button" href="missions.php">Missions</a><br><br>
                <ul>
                    <h1>
                        Reports:
                    </h1>
                </ul>
                <li>
                    Number of ongoing missions: <?= $db->query("SELECT count(id) as count FROM missions")->fetchObject()->count; ?>
                </li>
                <li>
                    Number of Spaceships on missions: <?= $db->query("SELECT count(id) as count FROM spaceships WHERE missions_id IS NOT NULL")->fetchObject()->count; ?>
                </li>
                <li>
                    Number of Astronauts on missions: <?= $db->query(
                        "SELECT count(astronauts.id) AS count FROM astronauts " .
                        "JOIN spaceships ON astronauts.spaceships_id = spaceships.id " .
                        "WHERE astronauts.spaceships_id IS NOT NULL AND spaceships.missions_id IS NOT NULL;"
                    )->fetchObject()->count; ?>
                </li>
            </div>
            <table id="scaleButton">
                <tr>
                    <th>
                        <div class="th-content">
                            <div>Ongoing Missions</div>
                            <div class="button-container">
                                <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                                <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                            </div>
                        </div>
                    </th>
                </tr>
                <?php
                    $query = $db->query("SELECT title FROM missions");
                    while($result = $query->fetchObject()):?> 
                        <tr>
                            <td>
                                <?= $result->title ?>
                            </td>
                        </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <script type="module" src="../js/home3d.js"></script>
    </body>
</html>