<?php

declare(strict_types=1);


require_once(__DIR__ . '/../database/users.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');

$db = getDatabaseConnection();
$name = htmlspecialchars($_GET['name']);
$session = new Session();
$session->generateToken();
$username = $session->getUsername();


if(!user::checkName($name, $errors)){
    if (!empty($errors)) {
        $error_message = "";
        foreach ($errors as $error) {
            $error_message .= $error . "\n";
        }
        echo json_encode($error_message);
        exit();
    }
}
user::changeName($db,$username, $name);
echo json_encode('');