<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/ticket.php');

?>

<?php
function drawTicketPreview(PDO $db, array $tickets)
{ ?>
    <div class="ticketPrev">
        <div class="container">
            <?php
            if (!empty($tickets)) {
                foreach ($tickets as $ticket) { ?>
                    <button class="slideBL" onclick="scrollHContainer(200,'left',this.parentNode)"></button>
                    <a href="../pages/ticket_details.php?id=<?php echo $ticket['id']?>">
                        <div class="card">
                            <div class="content">
                                <header>
                                    <h1><?= htmlspecialchars(strlen($ticket['subject']) > 10 ? substr($ticket['subject'], 0, 10) . "..." : $ticket['subject']) ?></h1>
                                    <h2 class="status"><?= htmlspecialchars(ticket::getStatus($db, $ticket['status'])) ?></h2>

                                </header>
                                <p>
                                    <?= htmlspecialchars(strlen($ticket['content']) > 220 ? substr($ticket['content'], 0, 220) . "..." : $ticket['content']) ?>
                                </p>
                            </div>
                        </div>
                    </a>
                    <button class="slideB" onclick="scrollHContainer(200,'right',this.parentNode)"></button>
                    <?php
                }
            } else {
                ?>
                <a href="../pages/ticketPage.php">
                    <div class="card">
                        <div class="content">
                            <header>
                                <h2 class="status"></h2>
                                <h1>No tickets</h1>
                            </header>
                            <p>
                                You still don't have any tickets
                            </p>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
        <div class="container">
            <button class="slideBL" onclick="scrollHContainer(200,'left',this.parentNode)"></button>
            <a href="../pages/ticketPage.php">
                <div class="card">
                    <div class="statistics">
                        <header>
                            <h1>Number of tickets</h1>
                        </header>
                        <p>
                            <?php
                            echo count($tickets);
                            ?>
                        </p>
                    </div>
                </div>
            </a>
           <button class="slideB" onclick="scrollHContainer(200,'right',this.parentNode)"></button>
            <button class="slideBL" onclick="scrollHContainer(200,'left',this.parentNode)"></button>
            <a href="../pages/ticketPage.php">
                <div class="card">
                    <div class="statistics">
                        <header>
                            <h1>Tickets Open</h1>
                        </header>
                        <p>
                            <?php

                            $open = 0;
                            foreach ($tickets as $ticket) {
                                if ($ticket['status'] == 0) {
                                    $open++;
                                }
                            }
                            echo $open;
                            ?>
                        </p>
                    </div>
                </div>
            </a>
            <button class="slideB" onclick="scrollHContainer(200,'right',this.parentNode)"></button>
            <button class="slideBL" onclick="scrollHContainer(200,'left',this.parentNode)"></button>
            <a href="../pages/ticketPage.php">
                <div class="card">
                    <div class="statistics">
                        <header>
                            <h1>Tickets in progress</h1>
                        </header>
                        <p>
                            <?php

                            $inProgress = 0;
                            foreach ($tickets as $ticket) {
                                if ($ticket['status'] == 1) {
                                    $inProgress++;
                                }
                            }
                            echo $inProgress;
                            ?>
                        </p>
                    </div>
                </div>
            </a>
            <button class="slideB" onclick="scrollHContainer(200,'right',this.parentNode)"></button>
            <button class="slideBL" onclick="scrollHContainer(200,'left',this.parentNode)"></button>
            <a href="../pages/ticketPage.php">
                <div class="card">
                    <div class="statistics">
                        <header>
                            <h1>Tickets Closed</h1>
                        </header>
                        <p>
                            <?php

                            $closed = 0;
                            foreach ($tickets as $ticket) {
                                if ($ticket['status'] == 2) {
                                    $closed++;
                                }
                            }
                            echo $closed;
                            ?>
                        </p>
                    </div>
                </div>
            </a>
            <button class="slideB" onclick="scrollHContainer(200,'right',this.parentNode)"></button>
        </div>
    </div>

    <?php
} ?>
