<?php

declare(strict_types=1);


require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');

$db = getDatabaseConnection();
$session = new Session();
$session->generateToken();

$ticketId = $_GET['ticket_id'];
echo json_encode(ticket::deleteTicket($db, $ticketId));
