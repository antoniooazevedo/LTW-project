<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');

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

$errors = array();

if (!user::checkPassword($_POST['psw'], $errors)) {
    if (!empty($errors)) {
        $error_message = "";
        foreach ($errors as $error) {
            $error_message .= $error . "\\n";
        }
        ?>
        <script>
            window.alert("<?php echo $error_message ?>");
            window.location.href = "../pages/profile.php?error=2";
        </script>
        <?php
        exit;
    }
}
user::changePassword($db,$session->getUsername(), $_POST['psw']);
header('Location: ../pages/login.php?success=3');

