<?php
require_once '../utils/session.php';
require_once '../utils/misc.php';
require_once '../database/ticket.php';
require_once '../database/connection.db.php';

$session = new Session();

if ($session->getUsername() == null) die(header('Location: /../pages/login.php'));

$db = getDatabaseConnection();
$ticketId = htmlspecialchars($_GET['ticket_id']);
$hashtags = ticket::getTicketHashtagNames($db, $ticketId);
$hashtagName = htmlspecialchars($_GET['hashtag']);
$hashtagName = str_replace(' ', '', $hashtagName);
$hashtagName = str_replace('#', '', $hashtagName);

$add = $_GET['action'];

if ($add == 'remove'){
    ticket::removeHashtagOfTicket($db, $ticketId, $hashtagName);
    echo json_encode('');
} else {
    if ($hashtagName == '') {
        echo json_encode('empty');
        exit();
    }
    misc::addHashtagToTicket($db,$hashtagName,$ticketId);
    if (in_array($hashtagName, $hashtags)) {
        echo json_encode('alreadyExists');
        exit();
    }
    echo json_encode($hashtagName);
}
exit();