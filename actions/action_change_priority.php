<?php
declare(strict_types=1);


require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();
$ticketId = $_GET['ticket_id'];
$priority = htmlspecialchars($_GET['priority']);
$priority = ticket::getPriorityId($db, $priority);
ticket::changePriority($db, $ticketId, $priority);
echo json_encode('');
