<?php
include "header.php";
require_once "db.php";
require_once "sql_error_handler.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $password_re = trim($_POST["password_re"]);

    // Basic validations
    if (empty($username) || empty($email) || empty($password) || empty($password_re)) {
        $_SESSION['message'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email address.";
    } elseif ($password !== $password_re) {
        $_SESSION['message'] = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert into database
            $sql = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $sql->bindParam(":username", $username, PDO::PARAM_STR);
            $sql->bindParam(":email", $email, PDO::PARAM_STR);
            $sql->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
            $sql->execute();

            $_SESSION['message'] = "Registration successful. You can now log in.";
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['message'] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/<?=$_SESSION["theme"]?>_theme.css">
        <title>Register</title>
    </head>
    <body>
        <?php require_once "alert_message.php"; ?>
        <?php require_once "navbar.php"; ?>
        <div class="topbar">Register</div>
        <form class="form-login" action="register.php" method="post">
            <?php 
            $username = $_POST['username'] ?? ""; 
            $email = $_POST['email'] ?? ""; 
            ?>
            <input type="text" id="username" name="username" placeholder="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"><br>
            <input type="email" id="email" name="email" placeholder="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"><br>
            <input type="password" id="password" name="password" placeholder="password"><br>
            <input type="password" id="password_re" name="password_re" placeholder="password again"><br>
            <button class="button" type="submit">Register</button>
        </form>
        <?php if (isset($error) && !empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
    </body>
</html>