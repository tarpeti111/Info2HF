<?php
require_once "header.php";
require_once "db.php";

// Get the current user's username from the session
$username = $_SESSION['user']["username"] ?? "";

// Fetch data from the database for reports
$missionCount = $db->query("SELECT count(id) as count FROM missions")->fetchObject()->count;
$spaceshipCount = $db->query("SELECT count(id) as count FROM spaceships WHERE missions_id IS NOT NULL")->fetchObject()->count;
$astronautCount = $db->query(
    "SELECT count(astronauts.id) AS count FROM astronauts " . 
    "JOIN spaceships ON astronauts.spaceships_id = spaceships.id " . 
    "WHERE astronauts.spaceships_id IS NOT NULL AND spaceships.missions_id IS NOT NULL"
)->fetchObject()->count;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/<?= htmlspecialchars($_SESSION["theme"], ENT_QUOTES, 'UTF-8') ?>_theme.css">
    <title>Home</title>
    <script src="../js/sort_table.js" defer></script>
    <script type="importmap"><?php include "../json/importmap.json"; ?></script>
</head>
<body class="<?= htmlspecialchars($_SESSION["theme"], ENT_QUOTES, 'UTF-8') ?>">
    <canvas id="3dCanvas" width="600" height="400"></canvas> <!-- Canvas for 3D rendering -->
    <?php require_once "alert_message.php"; ?>
    <?php include "navbar.php"; ?>

    <div class="home-topbar">
        <div>Welcome <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <div class="home-content">
        <div>
            <h1>Navigation:</h1>
            <a class="button" href="astronauts.php">Astronauts</a><br><br>
            <a class="button" href="spaceships.php">SpaceShips</a><br><br>
            <a class="button" href="missions.php">Missions</a><br><br>

            <h1>Reports:</h1>
            <ul>
                <li>Number of ongoing missions: <?= $missionCount ?></li>
                <li>Number of Spaceships on missions: <?= $spaceshipCount ?></li>
                <li>Number of Astronauts on missions: <?= $astronautCount ?></li>
            </ul>
        </div>

        <table>
            <tr>
                <th>
                    <div class="th-content">
                        <div>Ongoing Missions</div>
                    </div>
                </th>
            </tr>
            <?php
                // Get all mission titles from the database
                $query = $db->query("SELECT title FROM missions");
                while ($result = $query->fetchObject()):
            ?> 
                <tr>
                    <td><?= htmlspecialchars($result->title, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script type="module" src="../js/home3d.js"></script>
</body>
</html>