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
                            <img class="button" onclick= "sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Start Date</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>End Date</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(2, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(2, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Status</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(3, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(3, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Launch Location</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(4, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(4, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Destination</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(5, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(5, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Number of<br>Astronauts</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(6, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(6, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Number of<br>Ships</div>
                        <div class="button-container">
                            <img class="button" onclick= "sortTable(7, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick= "sortTable(7, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['access_level'] === 'admin'): ?>
                <th>Update</th>
                <th>Delete</th>
                <?php endif; ?>
                <th>Image</th>
            </tr>
            <?php
                $query = $db->query("SELECT * FROM missions");
                while ($row = $query->fetchObject()):?>
                <tr>
                    <td><?= $row->title ?? "" ?></td>
                    <td><?= $row->start_date ?? ""  ?></td>
                    <td><?= ($row->end_date == "0000-00-00") ? "" : $row->end_date ?></td>
                    <td><?= ucfirst($row->status) ?? "" ?></td>
                    <td><?= ucfirst($row->launch_location) ?? "" ?></td>
                    <td><?= ucfirst($row->destination) ?? "" ?></td>
                    <?php
                        $statement = "SELECT count(astronauts.id) as idCount FROM astronauts JOIN spaceships ON spaceships.id = astronauts.spaceships_id JOIN missions ON spaceships.missions_id = missions.id WHERE missions.id = :id";
                        $statement = $db->prepare($statement);
                        $statement->bindParam(":id", $row->id, PDO::PARAM_INT);
                        $statement->execute();
                        $astronautsCount = $statement->fetchObject()->idCount;
                    ?>
                    <td>
                        <?= $astronautsCount ?>
                    </td>
                    <?php
                        $statement = "SELECT count(spaceships.id) as idCount FROM spaceships JOIN missions ON spaceships.missions_id = missions.id WHERE missions.id = :id";
                        $statement = $db->prepare($statement);
                        $statement->bindParam(":id", $row->id, PDO::PARAM_INT);
                        $statement->execute();
                        $astronautsCount = $statement->fetchObject()->idCount;
                    ?>
                    <td>
                        <?= $astronautsCount ?>
                    </td>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['access_level'] === 'admin'): ?>
                        <td class="td-button">
                            <a class="button-table button-update" href="missions_update.php?id=<?= $row->id ?>">Update</a>
                        </td>
                        <td class="td-button">
                            <a class="button-table button-delete" href="delete_entry.php?id=<?= $row->id ?>">Delete</a>
                        </td>
                    <?php endif; ?>
                    <td></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <script type="module" src="../js/three.js"></script>
        <script src="../js/search_table.js"></script>
    </body>
</html>