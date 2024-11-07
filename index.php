<?php
    require 'db.php' ;
    $db = getDb("localhost", "SpaceMissions", "root", "");
?>

<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Space Mission Manager</title>
    </head>
    <body>
        <?php require 'header.php';?>
        <table>
            <tr>
                <th>Frist Name</th>
                <th>Last Name</th>
                <th>Occupation</th>
            </tr>
            <?php
                $query = $db->query("SELECT * FROM astronauts");
                while ($row = $query->fetchObject()):
            ?>
                <tr>
                    <td><?= $row->first_name ?></td>
                    <td><?= $row->last_name ?></td>
                    <td><?= $row->occupation ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <table>
            <tr>
                <th>Ship Name</th>
                <th>Type</th>
                <th>Crew</th>
            </tr>
            <?php
                $query = $db->query("SELECT * FROM spaceships");
                while ($row = $query->fetchObject()):
            ?>
                <tr>
                    <td><?= $row->name ?></td>
                    <td><?= $row->type ?></td>
                    <td>
                        <?php
                            $crewQuery = $db->query("SELECT * FROM astronauts WHERE astronauts.spaceships_id = $row->id");
                            while ($crewMember = $crewQuery->fetchObject()):
                        ?>
                            <?= $crewMember->first_name; ?> <?= $crewMember->last_name; ?> <br>
                        <?php endwhile; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </body>
</html>