<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../utils/misc.php');

    $db = getDatabaseConnection();
    $session = new Session();
    $session->generateToken();

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        echo "<script>alert('Invalid token')</script>";
        die(header('Location: /../pages/departments.php'));
    }

    if (isset($_POST['action']) && htmlspecialchars($_POST['action']) == 'add'){
        Misc::addDepartment($db, htmlspecialchars($_POST['department']));
    } elseif (isset($_POST['action']) && htmlspecialchars($_POST['action']) == 'remove'){
        Misc::removeDepartment($db, htmlspecialchars($_POST['department']));
    }

    die(header('Location: /../pages/departments.php'));
?>