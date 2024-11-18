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
                <th>
                    <div class="th-content">
                        <div>Name</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Occupation</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Ship Name</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Mission</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <?php if(isset($_SESSION['user'])): ?>
                <th>Update</th>
                <th>Delete</th>
                <?php endif; ?>
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
                    <?php if(isset($_SESSION['user'])): ?>
                    <td class="td-button">
                        <a class="button button-update" href="">Update</a>
                    </td>
                    <td class="td-button">
                        <a class="button button-delete" href="">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
        <script type="module" src="../js/three.js"></script>
    </body>
</html>