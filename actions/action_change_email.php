<?php
    require_once(__DIR__ . '/../database/users.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');

    $db = getDatabaseConnection();
    $session = new Session();
    $session->generateToken();

    $email = htmlspecialchars($_GET['email']);
    $emailV = User::getEmail($db, $email);
    $username = $session->getUsername();

    if($emailV){
        echo json_encode('Email already exists!');
        exit();
    }elseif (!user::checkEmail($email, $errors)) {
        if (!empty($errors)) {
            $error_message = "";
            foreach ($errors as $error) {
                $error_message .= $error . "\n";
            }
            echo json_encode($error_message);
            exit();
        }
    }
    user::changeEmail($db, $username, $email);
    echo json_encode('');