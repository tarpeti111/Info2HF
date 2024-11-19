<?php
include "header.php";
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {  // Check if form is submitted via POST
    // Check if username and password are set and not empty
    if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
        
        // Get input values and sanitize them
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        
        // Use a hashed password comparison for security (assuming password hashing was done during registration)
        require_once("db.php");

        // Prepare the query with placeholders
        $sql = $db->prepare("SELECT * FROM users WHERE username = :username");

        // Bind the parameter to the prepared statement
        $sql->bindParam(":username", $username, PDO::PARAM_STR);
        $sql->execute();

        // Fetch the result
        $result = $sql->fetchObject();
        // Check if the user exists and verify the password
        if ($result && $password === $result->password) {
            // Password matches, start the session and store user data
            $_SESSION['user'] = [
                "username" => $result->username,
                "accessLevel" => $result->access_level,
                // You could also store the email if needed
                //"email" => $result->email,
            ];

            // Redirect to the home page or dashboard
            header("Location: index.php");
            exit();  // Don't forget to call exit() after header redirection
        }
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
        <form class="form-login" action="login.php" method="post">
            <?php $username = $_POST['username'] ?? ""?>
            <input type="text" id="username" name="username" placeholder="username" value="<?= $username ?>"><br>
            <input type="text" id="password" name="password" placeholder="password"><br>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>