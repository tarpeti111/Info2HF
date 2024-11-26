<?php
require_once "header.php";
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $sql = $db->prepare("SELECT * FROM users WHERE username = :username");
        $sql->bindParam(":username", $username, PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetchObject();
        var_dump($password, $result->password);

        if ($result && password_verify($password, $result->password)) { // Verify password
            $_SESSION['user'] = [
                "username" => $result->username,
                "access_level" => $result->access_level,
            ];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['message'] = "Both fields are required.";
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
        <?php require_once "alert_message.php"; ?>
        <?php require "navbar.php"; ?>
        <div class="topbar">Log In</div>
        <form class="form-login" action="login.php" method="post">
            <?php $username = $_POST['username'] ?? ""?>
            <input type="text" id="username" name="username" placeholder="username" value="<?= $username ?>"><br>
            <input type="text" id="password" name="password" placeholder="password"><br>
            <button class="button" type="submit">Submit</button><br>
            <div class="register-text">Don't have an account yet?<a href="register.php">Register Here</a></div>
        </form>
    </body>
</html>