<?php
    declare(strict_types=1);

    require_once '../utils/session.php';
    require_once '../database/connection.db.php';
    require_once '../database/faq.php';
    require_once '../database/users.php';

    $session = new Session();
    $session->generateToken();

    $db = getDatabaseConnection();

    $faq = new FAQ();
    $result = $faq->getAllFAQ();

    $isAgent = User::isAgent($db, $session->getUsername());

    if ($isAgent) {
        $id = htmlspecialchars($_POST['edit_id']);
        $question = htmlspecialchars($_POST['question']);
        $answer = htmlspecialchars($_POST['answer']);

        $faq->updateFAQ($id, $question, $answer);

        header('Location: ../pages/faq.php');
    } else {
        header('Location: ../pages/faq.php');
    }
?>