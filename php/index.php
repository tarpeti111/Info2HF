<?php
    include "header.php";
?>

<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/main.css">
        <script type="importmap">
            {
                "imports": {
                    "three": "https://cdn.jsdelivr.net/npm/three@0.170.0/build/three.module.js",
                    "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.170.0/examples/jsm/"
                }
            }
</script>
        <title>Space Mission Manager</title>
    </head>
    <body>
        <table>
            <tr>
                <th>Frist Name</th>
                <th>Last Name</th>
                <th>Occupation</th>
                <th></th>
            </tr>
            <?php
                $query = $db->query("SELECT * FROM astronauts");
                while ($row = $query->fetchObject()):
            ?>
                <tr>
                    <td><?= $row->first_name ?></td>
                    <td><?= $row->last_name ?></td>
                    <td><?= ucfirst($row->occupation) ?></td>
                    <td>
                        <div>
                            <script type="module" src="../js/three.js"></script>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button><a href="spaceships_view.php">AA</a></button>
    </body>
</html>