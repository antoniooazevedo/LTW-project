<?php

declare(strict_types=1);


require_once(__DIR__ . '/../database/users.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');

$db = getDatabaseConnection();
$session = new Session();
$session->generateToken();

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    echo "<script>alert('Invalid token')</script>";
    die(header('Location: /../pages/departments.php'));
}

$user = User::getUser($db, htmlspecialchars($_POST['username']));

if(user::sameUName($db,$session->getUsername(),htmlspecialchars($_POST['username']))){
    ?>
    <script>
        window.alert("Insert a different username from the previous one!");
        window.location.href = "../pages/profile.php?error=5";
    </script>
    <?php
    exit;
} elseif ($user){
    ?>
    <script>
        window.alert("Username already in use!");
        window.location.href = "../pages/profile.php?error=6";
    </script>
    <?php
    exit;
}elseif (!user::checkUsername(htmlspecialchars($_POST['username']), $errors)) {
    if (!empty($errors)) {
        $error_message = "";
        foreach ($errors as $error) {
            $error_message .= $error . "\\n";
        }
        ?>
        <script>
            window.alert("<?php echo htmlspecialchars($error_message) ?>");
            window.location.href = "../pages/profile.php?error=7";
        </script>
        <?php
        exit;
    }
}

user::changeUName($db,$session->getUsername(), htmlspecialchars($_POST['username']));
header('Location: ../pages/login.php?success=4');
