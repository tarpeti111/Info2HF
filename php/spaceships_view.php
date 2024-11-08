<?php require 'header.php';?>
<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/main.css">
        <title>Spaceships</title>
    </head>
    <body>
        <table>
        <tr>
            <th>Ship Name</th>
            <th>Type</th>
            <th>Crew</th>
            <th>Mission</th>
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
    </body>
</html>