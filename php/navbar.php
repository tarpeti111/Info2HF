<?php
require_once("header.php");
$navLinks = [
    'index.php' => 'Home',
    'astronauts.php' => 'Astronauts',
    'spaceships_view.php' => 'SpaceShips',
    'missions.php' => 'Missions'
];
if(isset($_SESSION['user'])){
    if($_SESSION['user']['access_level'] == "admin"){
        $navLinks['users.php'] = "Users";
    }
}
$navLinks['switch_theme.php'] = "Switch Themes";
?>

<div class="topnav">
    <?php
    $curHref = basename($_SERVER['SCRIPT_NAME']);
    foreach ($navLinks as $href => $title):
        // Do not include Log Out in the loop as it will be added separately
        if ($href !== 'login.php'): ?>
            <a href="<?= $href ?>" class="button <?= ($href === $curHref) ? 'active' : '' ?>">
                <?= $title ?>
            </a>
        <?php endif;
    endforeach;

    // If the user is logged in, add the Log Out button and display username
    if (isset($_SESSION['user'])): ?>
        <div class="right">
            Logged in as: <?= htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?>
            <a class="button" href="logout.php">Log Out</a>
        </div>
    <?php else: ?>
        <div class="right">
            <a class="button" href="login.php">Log In</a>
        </div>
    <?php endif; ?>
</div>