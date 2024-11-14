<html !DOCTYPE>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require "style.php"; ?>
        <title>Log In</title>
    </head>
    <body>
        <?php require "navbar.php"; ?>
        <div class="topbar">Log In</div>
        <form action="login.php" method="post">
            <?php $username = isset($_POST['username']) ? $_POST['username'] : ""?>
            <input type="text" id="username" name="username" placeholder="username" value="<?= $username ?>"><br>
            <input type="text" id="password" name="password" placeholder="password"><br>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>