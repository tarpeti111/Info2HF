<?php
require_once "header.php";
require_once "db.php";
require_once "sql_error_handler.php";
require_once "validate_input.php";

// Check for admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['access_level'] !== 'admin') {
    $_SESSION['message'] = "Admin Access Required!";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "login.php"));
    exit();
}

// Fetch the access level options
$query = $db->prepare("SHOW COLUMNS FROM users LIKE 'access_level'");
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);
$access_levelOptions = [];
if ($result && strpos($result['Type'], 'enum') === 0) {
    preg_match("/^enum\((.*)\)$/", $result['Type'], $matches);
    $access_levelOptions = isset($matches[1]) ? str_getcsv($matches[1], ',', "'") : [];
}

// Initialize variables
$username = validateInput($_POST['username'] ?? "", 'string', 45);
$email = validateInput($_POST['email'] ?? "", 'email');
$password = $_POST['password'] ?? "";
$password_re = $_POST['password_re'] ?? "";
$access_level = strtolower(validateInput($_POST['access_level'] ?? "", 'string'));
$userId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    if (empty($username) || empty($email) || empty($password) || empty($password_re)) {
        $_SESSION['message'] = "All fields are required.";
    } elseif (!$email) {
        $_SESSION['message'] = "Invalid email address.";
    } elseif ($password !== $password_re) {
        $_SESSION['message'] = "Passwords do not match.";
    } elseif (!in_array($access_level, $access_levelOptions)) {
        $_SESSION['message'] = "Invalid access level type.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Determine if updating or inserting
            $isUpdate = $userId !== null;

            $query = $isUpdate 
                ? "UPDATE users 
                   SET username = :username, email = :email, password = :password, access_level = :access_level 
                   WHERE id = :id"
                : "INSERT INTO users (username, email, password, access_level) 
                   VALUES (:username, :email, :password, :access_level)";

            // Prepare and execute query
            $statement = $db->prepare($query);
            $statement->bindParam(":username", $username, PDO::PARAM_STR);
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
            $statement->bindParam(":access_level", $access_level, PDO::PARAM_STR);

            if ($isUpdate) {
                $statement->bindParam(":id", $userId, PDO::PARAM_INT);
            }

            $statement->execute();
            $_SESSION['message'] = $isUpdate ? "User updated successfully!" : "User added successfully!";
        } catch (PDOException $e) {
            $_SESSION['message'] = getErrorMessage($e);
            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.php"));
            exit();
        }

        header("Location: users.php");
        exit();
    }

    // Redirect back with validation errors
    header("Location: users_update.php" . ($userId ? "?id=" . $userId : ""));
    exit();
}

// Fetch user data for editing
if ($userId) {
    $query = $db->prepare("SELECT * FROM users WHERE id = :id");
    $query->bindParam(":id", $userId, PDO::PARAM_INT);
    $query->execute();
    if ($result = $query->fetchObject()) {
        $username = $result->username;
        $email = $result->email;
        $access_level = $result->access_level;
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/<?= $_SESSION["theme"] ?>_theme.css">
        <title>Spaceship Form</title>
        <script src="../js/add_remove_select.js"></script>
    </head>
    <body>
        <?php include "alert_message.php"; ?>
        <?php include "navbar.php"; ?>

        <form method="post" class="form-update">
            <div class="form-element-div">
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="form-element-div">
                <label>Email:</label>
                <input type="text" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="form-element-div">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-element-div">
                <label>Re-enter Password:</label>
                <input type="password" name="password_re" required>
            </div>
            <div class="form-element-div">
                <label>Access Level:</label>
                <select name="access_level">
                    <?php foreach ($access_levelOptions as $option): ?>
                        <option value="<?= $option ?>" <?= $option == $access_level ? 'selected' : '' ?>>
                            <?= ucfirst($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="button" type="submit">Submit</button>
        </form>
    </body>
</html>