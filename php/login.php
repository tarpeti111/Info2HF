<?php
    if(isset($_POST)){
        if(isset($_POST["username"]))
            $username = $_POST["username"];
        if(isset($_POST["password"]))
            $password = $_POST["password"];
        if(isset($_POST["email"]))
            $email = $_POST["email"];

        if($username != "" && $password != ""/* && $email != ""*/){
            require_once("db.php");
            $sql = $db->prepare("SELECT * FROM users WHERE username = :username AND password = :password /* AND email = :email*/");
            $sql->bindParam(":username", $username, PDO::PARAM_STR);
            $sql->bindParam(":password", $password, PDO::PARAM_STR);
            //$sql->bindParam(":email", $email, PDO::PARAM_STR);
            $sql->execute();
            $result = $sql->fetchObject();
            $_SESSION['user'] = [
                "username" => $result->username,
                //"email"=> $result->email,
                "accessLevel" => $result->access_level,
            ];
            //header("Location: index.php");
        }
    }
?>

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
        <?php if(isset($_SESSION['user'])){echo($_SESSION['user']['username']);} ?>
        <form action="login.php" method="post">
            <?php $username = isset($_POST['username']) ? $_POST['username'] : ""?>
            <input type="text" id="username" name="username" placeholder="username" value="<?= $username ?>"><br>
            <input type="text" id="password" name="password" placeholder="password"><br>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>