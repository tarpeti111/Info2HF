<?php
    require_once "header.php";
    require_once "db.php";
?>

<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require "style.php"; ?>
        <script type="importmap"><?php include "../json/importmap.json"; ?></script>
        <title>Space Mission Manager</title>
        <script src="../js/sort_table.js" defer></script>
    </head>
    <body>
        <canvas></canvas>
        <?php include "navbar.php"; ?>
        <div class="topbar">Astronauts</div>
        <table>
            <tr>
                <th onclick="sortTable(0)">Name</th>
                <th onclick="sortTable(1)">Occupation</th>
                <th onclick="sortTable(2)">Ship</th>
                <th onclick="sortTable(3)">Mission</th>
            </tr>
            <?php
                $query = $db->query("SELECT * FROM astronauts");
                while ($row = $query->fetchObject()):
            ?>
                <tr>
                    <td><?= $row->first_name ?> <?= $row->last_name ?></td>
                    <td><?= ucfirst($row->occupation) ?></td>
                    
                    <?php 
                    $spaceship_query = "SELECT missions_id, name FROM spaceships WHERE spaceships.id = $row->spaceships_id;";
                    $spaceship = $db->query($spaceship_query)->fetchObject();

                    $mission_query = "SELECT title FROM missions WHERE missions.id = $spaceship->missions_id";
                    $mission_title = $db->query($mission_query)->fetchObject();?>
        
                    <td><?= $spaceship->name ?></td>
                    <td><?= $mission_title->title ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <script type="module" src="../js/three.js"></script>
    </body>
</html>