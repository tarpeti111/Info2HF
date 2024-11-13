<?php 
    require_once("header.php");
    require_once("db.php");
?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include("style.php")?>
        <title>Spaceships</title>
        <script type="importmap"><?php include "../json/importmap.json"; ?></script>
        <script src="../js/sort_table.js" defer></script>
    </head>
    <body class="<?= $_SESSION["theme"] ?>">
        <?php include "navbar.php" ?>
        <div class="topbar">Space Ships</div>
        <table>
        <tr>
            <th>Ship Name
                <div class="button-container">
                    <img class="button" onclick="sortTable(0)" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                    <img src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                </div>
            </th>
            <th onclick="sortTable(1)">Type</th>
            <th onclick="sortTable(2)">Crew</th>
            <th onclick="sortTable(3)">Mission</th>
        </tr>
        <?php
            $query = $db->query("SELECT * FROM spaceships");
            while ($row = $query->fetchObject()):
        ?>
            <tr>
                <td><?= $row->name ?></td>
                <td><?= ucfirst($row->type) ?></td>
                <td>
                    <?php
                        $crewQuery = $db->query("SELECT first_name, last_name FROM astronauts WHERE astronauts.spaceships_id = $row->id");
                        while ($crewMember = $crewQuery->fetchObject()):
                    ?>
                        <?= $crewMember->first_name; ?> <?= $crewMember->last_name; ?> <br>
                    <?php endwhile; ?>
                </td>
                <td>
                        <?= $db->query("SELECT title FROM missions WHERE missions.id = $row->missions_id LIMIT 1")->fetchObject()->title ?> <br>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <canvas></canvas>
        <script type="module" src="../js/three.js"></script>
    </body>
</html>