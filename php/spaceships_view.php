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
                    <div>Type</div>
                    <div class="button-container">
                        <img class="button" onclick= "sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                        <img class="button" onclick= "sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                    </div>
                </div>
            </th>
            <th>
                <div class="th-content">
                    <div>Crew</div>
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
            <?php if(isset($_SESSION['user'])): ?>
            <th>Update</th>
            <th>Delete</th>
            <?php endif; ?>
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
                <?php if(isset($_SESSION['user'])): ?>
                <td class="td-button">
                    <a class="button button-update" href="spaceships_update.php?id=<?= $row->id ?>">Update</a>
                </td>
                <td class="td-button">
                    <a class="button button-delete" href="">Delete</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </table>
        <canvas></canvas>
        <script type="module" src="../js/three.js"></script>
    </body>
</html>