<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/users.php');

    $db = getDatabaseConnection();
    $session = new Session();
    $session->generateToken();

    if (isset($_POST['action']) && htmlspecialchars($_POST['action']) == 'assign'){
        User::assignAgentToDepartment($db, htmlspecialchars($_POST['username']), htmlspecialchars($_POST['department']));
    } elseif (isset($_POST['action']) && htmlspecialchars($_POST['action']) == 'unassign'){
        User::removeAgentFromDepartment($db, htmlspecialchars($_POST['username']), htmlspecialchars($_POST['department']));
    }

    die(header('Location: /../pages/agents.php'));
?>