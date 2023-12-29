<?php
require_once '../utils/session.php';
require_once '../database/ticket.php';
require_once '../database/connection.db.php';

$session = new Session();
$session->generateToken();

if ($session->getUsername() == null) die(header('Location: /../pages/login.php'));

$db = getDatabaseConnection();

if (isset($_GET['department'])) {
    $departmentId = ticket::getDepartmentId($db,htmlspecialchars($_GET['department']));
    $agents = user::getAgentsByDepartment($db, $departmentId);
    ticket::changeAgent($db, htmlspecialchars($_GET['ticket_id']), null);
    ticket::changeStatus($db, htmlspecialchars($_GET['ticket_id']), 0);
    echo json_encode($agents);
}
