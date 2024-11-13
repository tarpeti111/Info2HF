<?php
    require_once("header.php");
    $navLinks = [
        'index.php' => 'Home',
        'spaceships_view.php' => 'SpaceShips',
        'switch_theme.php' => 'SwitchThemes',
        '#about' => 'About',
    ];
?>

<div class="topnav">
    <?php
    $curHref = basename($_SERVER['SCRIPT_NAME']);
    foreach ($navLinks as $href => $title): ?>
            <a href="<?= $href ?>" class="button <?= ($href === $curHref) ? 'active' : '' ?>">
            <?= $title ?>
            </a>
    <?php endforeach; ?>
</div>