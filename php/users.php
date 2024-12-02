<?php
require_once "header.php";
require_once "db.php";

// Admin check
if (!isset($_SESSION['user']) || $_SESSION['user']['access_level'] !== "admin") {
    $_SESSION['message'] = "Admin access required";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/<?=$_SESSION["theme"]?>_theme.css">
        <script type="importmap"><?php include "../json/importmap.json"; ?></script>
        <script type="module">
            <?php include "../json/importmap.json";?>
        </script>
        <title>Users</title>
        <script src="../js/sort_table.js" defer></script>
    </head>
    <body>
        <canvas></canvas>
        <?php require_once "alert_message.php"; ?>
        <?php include "navbar.php"; ?>

        <div class="topbar">Users
            <div style="margin-left: 3%;"></div>
            <div class="search-input">
                <input type="text" id="searchInput" placeholder="Search">
            </div>
            <div class="right">
                <a href="users_update.php" class="button">Add new User</a>
            </div>
        </div>

        <table>
            <tr>
                <th>
                    <div class="th-content">
                        <div>Username</div>
                        <div class="button-container">
                            <img class="button" onclick="sortTable(0, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick="sortTable(0, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Email Address</div>
                        <div class="button-container">
                            <img class="button" onclick="sortTable(1, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick="sortTable(1, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Password Hash</div>
                    </div>
                </th>
                <th>
                    <div class="th-content">
                        <div>Access Level</div>
                        <div class="button-container">
                            <img class="button" onclick="sortTable(3, 'up')" src="../resources/images/up_arrow_white.png" alt="up_arrow_white.png">
                            <img class="button" onclick="sortTable(3, 'down')" src="../resources/images/down_arrow_white.png" alt="down_arrow_white.png">
                        </div>
                    </div>
                </th>
                <th>Update</th>
                <th>Delete</th>
            </tr>

            <?php
            $query = $db->query("SELECT * FROM users");

            // Check if the query is successful
            if ($query) {
                while ($row = $query->fetchObject()) :
                    // Sanitize output using htmlspecialchars to prevent XSS
                    $username = htmlspecialchars($row->username);
                    $email = htmlspecialchars($row->email);
                    $password = htmlspecialchars($row->password);
                    $accessLevel = htmlspecialchars($row->access_level);
                    ?>
                    <tr>
                        <td><?= $username ?></td>
                        <td><?= $email ?></td>
                        <td><?= $password ?></td>
                        <td><?= $accessLevel ?></td>
                        <td class="td-button">
                            <a class="button-table button-update" href="users_update.php?id=<?= $row->id ?>">Update</a>
                        </td>
                        <td class="td-button">
                            <a class="button-table button-delete" href="delete_entry.php?id=<?= $row->id ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; } else { ?>
                    <tr>
                        <td colspan="6">No users found</td>
                    </tr>
                <?php } ?>
        </table>
        <script type="module" src="../js/three.js"></script>
        <script src="../js/search_table.js"></script>
    </body>
</html>