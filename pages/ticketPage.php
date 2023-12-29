<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../database/users.php');
require_once(__DIR__ . '/../utils/session.php');
require_once (__DIR__ . '/../templates/drawTicketSequence.php');
require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../utils/misc.php');

$session = new Session();
$session->generateToken();

if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

$db = getDatabaseConnection();

$username = $session->getUsername();
$tickets = ticket::getTickets($db, $username);
$statuses = ticket::getAllStatuses($db);
$departments = ticket::getAllDepartments($db);
$hashtags = ticket::getAllHashtags($db);
$agents = user::getAllAgents($db);
$priorities = ticket::getAllPriorities($db);

?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ticket Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/ticket_page.css">
    <script src="../javascript/scripts.js" defer></script>
</head>
<?php drawHeader($username); ?>
<div class="ticketPage">
    <?php if(user::isAgent($db, $username)){
        ?>
        <div class = "filtering-box">
            <button id="filter">&#x1F50D;</button>
            <?php
            drawFilter($statuses, $departments, $hashtags, $agents, $priorities);
            ?>
        </div>
        <?php
    }
    ?>
    <div class="ticketContentBox">
        <?php
        drawTicketSequence($tickets, $db);
        ?>
    </div>
</div>



<?php drawFooter(); ?>
