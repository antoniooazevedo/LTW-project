<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/users.php');

    $db = getDatabaseConnection();
    $session = new Session();
    $session->generateToken();

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        echo "<script>alert('Invalid token')</script>";
        die(header('Location: /../pages/departments.php'));
    }

    if (isset($_POST['action']) && htmlspecialchars($_POST['action']) == 'promote' && isset($_POST['username'])){
        User::promoteUser($db, htmlspecialchars($_POST['username']));
    } elseif (isset($_POST['action']) && htmlspecialchars($_POST['action']) == 'demote' && isset($_POST['username']) && htmlspecialchars($_POST['username']) != $session->getUsername()){
        User::demoteUser($db, htmlspecialchars($_POST['username']));
    }

    die(header('Location: /../pages/agents.php'));
?>