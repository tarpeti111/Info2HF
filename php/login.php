<?php
require_once "header.php";
require_once "db.php";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        // Sanitize input to avoid XSS
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        // Prepare and execute SQL query to find user
        $sql = $db->prepare("SELECT * FROM users WHERE username = :username");
        $sql->bindParam(":username", $username, PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetchObject();

        // Verify password
        if ($result && password_verify($password, $result->password)) {
            $_SESSION['user'] = [
                "username" => $result->username,
                "access_level" => $result->access_level,
            ];

            // Redirect to the page they were trying to access (or default to index.php)
            $redirectTo = $_SESSION['redirect_to'] ?? 'index.php';
            header("Location: $redirectTo");
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['message'] = "Both fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/<?= htmlspecialchars($_SESSION["theme"], ENT_QUOTES, 'UTF-8') ?>_theme.css">
    <title>Log In</title>
</head>
<body>
    <?php require_once "alert_message.php"; ?>
    <?php require "navbar.php"; ?>

    <div class="topbar">Log In</div>
    
    <!-- Login Form -->
    <form class="form-login" action="login.php" method="post">
        <?php $username = $_POST['username'] ?? "" ?>
        <input type="text" id="username" name="username" placeholder="Username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"><br>
        
        <input type="password" id="password" name="password" placeholder="Password"><br> <!-- Corrected type to password -->
        
        <button class="button" type="submit">Submit</button><br>
        
        <div class="register-text">Don't have an account yet? <a href="register.php">Register Here</a></div>
    </form>
</body>
</html>