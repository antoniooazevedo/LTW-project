<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticket.php');
    require_once(__DIR__ . '/../database/uploads.php');

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();
    $session->generateToken();
    $db = getDatabaseConnection();

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        echo "<script>alert('Invalid token')</script>";
        die(header('Location: /../pages/departments.php'));
    }

    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $hashtags = htmlspecialchars($_POST['hashtags']);
    $department = htmlspecialchars($_POST['department']);

    $documents = Upload::uploadFile($session->getUsername(), false);

    ticket::addTicket($db, $session->getUsername(),$department, $title, $content, $hashtags, $documents);

    header('Location: ../pages/index.php')
?>
