<?php
require_once '../utils/session.php';
require_once '../database/ticket.php';
require_once '../database/connection.db.php';

$session = new Session();
$session->generateToken();

if ($session->getUsername() == null) die(header('Location: /../pages/login.php'));

$db = getDatabaseConnection();


$ticketId = $_POST['id'] ?? null;

$logs = ticket::getLogs($db, $ticketId);

echo json_encode($logs);
?>