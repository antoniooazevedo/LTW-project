<?php
declare(strict_types=1);


require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();
$ticketId = htmlspecialchars($_GET['ticket_id']);
$agent = htmlspecialchars($_GET['agent']);
if($agent === 'None'){
    $agent = null;
}
ticket::changeAgent($db, $ticketId, $agent);
echo json_encode('');
