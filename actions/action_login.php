<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/users.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();
    $session->generateToken();
    $db = getDatabaseConnection();
    $user = User::getUserUsernamePassword(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']));

    if ($user){
        $session->setUsername($user->username);
        $session->setLogin();
        header('Location: ../pages/index.php');
        die();
    }
    header('Location: ../pages/login.php?error=1');
