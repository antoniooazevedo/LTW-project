<?php
declare(strict_types=1);


require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();
$ticketId = $_GET['ticket_id'];
$status = htmlspecialchars($_GET['ticket_status']);
$status = ticket::getStatusId($db, $status);
ticket::changeStatus($db, $ticketId, $status);
echo json_encode('');
?>