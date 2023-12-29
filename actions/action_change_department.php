<?php
declare(strict_types=1);


require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();
$ticketId = htmlspecialchars($_GET['ticket_id']);
$department = htmlspecialchars($_GET['department']);
$department = ticket::getDepartmentId($db, $department);
ticket::changeDepartment($db, $ticketId, $department);
echo json_encode('');
