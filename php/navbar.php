<?php
require_once("header.php");
$navLinks = [
    'index.php' => 'Home',
    'astronauts.php' => 'Astronauts',
    'spaceships_view.php' => 'SpaceShips',
    'missions.php' => 'Missions',
    'switch_theme.php' => 'SwitchThemes',
];
if (!isset($_SESSION['user'])) {
    $navLinks['login.php'] = 'Log In';
}
?>

<div class="topnav">
    <?php
    $curHref = basename($_SERVER['SCRIPT_NAME']);
    foreach ($navLinks as $href => $title):
        // Do not include Log Out in the loop as it will be added separately
        if ($href !== 'logout.php'): ?>
            <a href="<?= $href ?>" class="button <?= ($href === $curHref) ? 'active' : '' ?>">
                <?= $title ?>
            </a>
        <?php endif;
    endforeach;

    // If the user is logged in, add the Log Out button and display username
    if (isset($_SESSION['user'])): ?>
        <a href="logout.php" class="button <?= ('logout.php' === $curHref) ? 'active' : '' ?>">Log Out</a>
        <div class="right">Logged in as: <?= htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php else: ?>
        <div class="right"></div>
    <?php endif; ?>
</div>