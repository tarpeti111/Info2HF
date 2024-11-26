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
                            <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Occupation</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Ship Name</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(2, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(2, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Mission</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(3, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(3, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Birth Date</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(4, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(4, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
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
                    $spaceship = "";
                    if($row->spaceships_id !== NULL){
                        $spaceship_query = "SELECT missions_id, name FROM spaceships WHERE spaceships.id = $row->spaceships_id;";
                        $spaceship = $db->query($spaceship_query)->fetchObject();
                        if(isset($spaceship->missions_id)){
                            $mission_query = "SELECT title FROM missions WHERE missions.id = $spaceship->missions_id";
                            $mission_title = $db->query($mission_query)->fetchObject();
                        }
                    }?>
        
                    <td><?= $spaceship->name ?? "" ?></td>
                    <td><?= ($row->spaceships_id !== NULL) ? $mission_title->title : "" ?></td>
                    <td><?= $row->birth_date ?></td>
                    <?php if(isset($_SESSION['user'])): ?>
                    <td class="td-button">
                        <a class="button-table button-update" href="astronauts_update.php?id= <?= $row->id ?>">Update</a>
                    </td>
                    <td class="td-button">
                        <a class="button-table button-delete" href="delete_entry.php?id= <?= $row->id ?>">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
        <script type="module" src="../js/three.js"></script>
        <script src="../js/search_table.js"></script>
    </body>
</html>