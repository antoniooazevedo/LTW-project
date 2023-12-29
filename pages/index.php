<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/ticketPrev.tpl.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/misc.php');

$session = new Session();
$session->generateToken();

$db = getDatabaseConnection();
$departments = ticket::getAllDepartments($db);
if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));


$tickets = ticket::getTickets($db, $session->getUsername());
//todo style this page better
?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Ticket Master</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="../javascript/scripts.js" defer></script>
        <link rel="stylesheet" href="../css/mainPage.css">
        <link rel="stylesheet" href="../css/ticket_page.css">
    </head>
<?php
drawHeader($session->getUsername()); ?>
    <section id="hero">
        <div class="heroHeader">
            <h1>Welcome to TicketMaster</h1>
            <p>World's number one ticketing solution for your company</p>
            <div class="createATicketButton">
                <a href="../pages/create_ticket.php">
                    Create a ticket
                </a>
            </div>
        </div>
        <img src="../images/ticketingImage.png" alt="hero">
    </section>
    <section id="generalInfo">
        <table class="generalInfoTable">
            <tr>
                <th>Agents available</th>
            </tr>
            <tr>
                <td>
                    <?php
                    $agents = user::getAllAgents($db);
                    echo count($agents);
                    ?>
                </td>
            </tr>
        </table>
        <table class="generalInfoTable">
            <tr>
                <th>Open Tickets</th>
                <th>Assigned Tickets</th>
                <th>Closed Tickets</th>
            </tr>
            <tr>
                <td>
                    <?php
                    $openTickets = ticket::getTicketsByStatus($db, 0);
                    echo count($openTickets);
                    ?>
                </td>
                <td>
                    <?php
                    $assignedTickets = ticket::getTicketsByStatus($db, 1);
                    echo count($assignedTickets);
                    ?>
                </td>
                <td>
                    <?php
                    $closedTickets = ticket::getTicketsByStatus($db, 2);
                    echo count($closedTickets);
                    ?>
                </td>
        </table>
    </section>

<?php
drawFooter();
?>