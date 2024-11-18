<?php
    require_once("header.php");
    $navLinks = [
        'index.php' => 'Home',
        'astronauts.php' => 'Astronatus',
        'spaceships_view.php' => 'SpaceShips',
        'switch_theme.php' => 'SwitchThemes',
    ];
    if(!isset($_SESSION['user'])){
        $navLinks['login.php'] = 'Log In';
    }
    else{
        $navLinks['logout.php'] = 'Log Out';
    }
?>

<div class="topnav">
    <?php
    $curHref = basename($_SERVER['SCRIPT_NAME']);
    foreach ($navLinks as $href => $title): ?>
            <a href="<?= $href ?>" class="button <?= ($href === $curHref) ? 'active' : '' ?>">
            <?= $title ?>
            </a>
    <?php endforeach;
    if(isset($_SESSION['user'])):?>
    <div class="right">Logged in as: <?= $_SESSION['user']['username'] ?></div>
    <?php else: ?>
    <div class="right"></div>
    <?php endif; ?>
</div>