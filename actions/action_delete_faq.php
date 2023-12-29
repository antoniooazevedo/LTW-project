<?php
    declare(strict_types=1);

    require_once '../utils/session.php';
    require_once '../database/connection.db.php';
    require_once '../database/faq.php';
    require_once '../database/users.php';

    $session = new Session();
    $session->generateToken();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        die();
    }

    $db = getDatabaseConnection();

    $faq = new FAQ();
    $result = $faq->getAllFAQ();

    $isAgent = User::isAgent($db, $session->getUsername());

    if ($isAgent) {
        $id = $_POST['id'];

        $faq->deleteFAQ($id);

        header('Location: ../pages/faq.php');
    } else {
        header('Location: ../pages/faq.php');
    }
