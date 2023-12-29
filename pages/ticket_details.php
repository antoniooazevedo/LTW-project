<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/drawTicketSequence.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/faq.php');

$session = new Session();
$session->generateToken();

if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));
$db = getDatabaseConnection();

$username = $session->getUsername();
$ticketId = htmlspecialchars($_GET['id']) ?? null;
$ticket = ticket::getTicketById($db, $ticketId);
$isAgent = user::isAgent($db, $username);
$statuses = ticket::getAllStatuses($db);
$agents = user::getAgentsByTicketDepartment($db, $ticketId);
$departments = ticket::getAllDepartments($db);
$priorities = ticket::getAllPriorities($db);
$files = ticket::getDocument($db, $ticketId);
$pfp = User::getPfp($db, $ticket['author_username']);
$pfpResponse = User::getPfp($db, $username);
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ticket Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="module" src="../javascript/ticket.js" defer></script>
    <link rel="stylesheet" href="../css/ticket.css">
</head>
<?php drawHeader($session->getUsername()); ?>
<div class="ticketDetails">
    <?php drawNavBarTicket(); ?>
    <input type="hidden" id="ticketId" value="<?php echo $ticket['id'] ?>">
    <div class="ticketContainerBox">
        <div class="auxDiv">
            <h2><?php echo htmlspecialchars(strlen($ticket['subject']) > 33 ? substr($ticket['subject'], 0, 33) . "..." : $ticket['subject']) ?></h2>
            <?php
            if (ticket::canModifyTicket($db, $username, $ticket)) {
                ?>
                <button class="edit" id="deleteTicket"> &#128465;</button>
                <?php
            }
            ?>
        </div>
        <div class="status-priority">
            <div class="editable">
                <?php
                $statusColor = '';
                if ($ticket['status'] == 0) {
                    $statusColor = 'green';
                } else if ($ticket['status'] == 1) {
                    $statusColor = '#be9801';
                } else {
                    $statusColor = 'red';
                }
                ?>
                <p id="ticketStatus" style="color: <?php echo $statusColor; ?>">
                    <?php echo htmlspecialchars(ticket::getStatusName($db, intval($ticket['status']))); ?>
                </p>
                <?php
                if (ticket::canModifyTicket($db, $username, $ticket)) { ?>
                    <button class="edit" id="statusEdit"> &#9998;</button>
                    <form class="editForm" id="statusChangeForm">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id'] ?>">
                        <label for="status"></label>
                        <select id="status" name="status">
                            <?php
                            foreach ($statuses as $status) {
                                $selected = ($status['id'] === $ticket['status']) ? 'selected' : ''; ?>
                                <option value="<?php echo htmlspecialchars($status['name']); ?>" <?php echo htmlspecialchars($selected); ?>><?php echo htmlspecialchars($status['name']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="submit" id="submit" value="Submit">
                    </form>
                    <?php
                }
                ?>
            </div>
            <?php
            if ($isAgent) { ?>
                <div class="editable">
                    <?php
                    if (ticket::canModifyTicket($db, $username, $ticket)) {
                        ?>
                        <form class="editForm" id="priorityChangeForm" action="../actions/action_change_priority.php">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id'] ?>">
                            <label for="priority"></label>
                            <select id="priority" name="priority">
                                <?php foreach ($priorities as $priority) {
                                    $selected = ($priority['id'] === $ticket['priority']) ? 'selected' : ''; ?>
                                    <option value="<?php echo htmlspecialchars($priority['name']); ?>" <?php echo htmlspecialchars($selected); ?>><?php echo htmlspecialchars($priority['name']); ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="submit" value="Submit">
                        </form>
                        <?php
                    }
                    ?>
                    <p id='ticketPriority'>
                        Priority: <?php echo ticket::getPriorityName($db, intval($ticket['priority'])); ?></p>
                    <?php
                    if (ticket::canModifyTicket($db, $username, $ticket)) { ?>
                        <button class="edit"> &#9998;</button>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="infoHeading">
            <div class="authorInfo">
                <img src="<?= $pfp ?>" alt="User" width="50" height="50">
                <h3><?php echo htmlspecialchars($ticket['author_username']) ?></h3>
            </div>
            <p><?php echo htmlspecialchars($ticket['date']); ?></p>
        </div>
        <div class="contentBox">
            <fieldset>
                <legend>Content</legend>
                <p><?php echo htmlspecialchars($ticket['content']); ?></p>
            </fieldset>
        </div>
        <div class="otherInfos">
            <div class="editable" id="agentDivChange">
                <p id="ticketAgent"><?php echo "Agent: " . htmlspecialchars($ticket['agent_username'] == null ? ' ' : $ticket['agent_username']); ?></p>
                <?php
                if (ticket::canModifyTicket($db, $username, $ticket)) {
                    ?>
                    <button class="edit" id='agentEdit'> &#9998;</button>
                    <form class="editForm" action="../actions/action_change_agent.php" id="agentChangeForm">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id'] ?>">
                        <label for="agent"></label>
                        <select id="agent" name="agent">
                            <?php foreach ($agents as $agent) {
                                $selected = ($agent['agent_username'] === $ticket['agent_username']) ? 'selected' : ''; ?>
                                <option value="<?php echo htmlspecialchars($agent['agent_username']); ?>" <?php echo htmlspecialchars($selected); ?>><?php echo htmlspecialchars($agent['agent_username']); ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="submit" value="Submit">
                    </form>
                    <?php
                }
                ?>
            </div>
            <div class="editable" id="departmentDivChange">
                <p id='ticketDepartment'><?php
                    $ticketDep = ticket::getDepartmentName($db, intval($ticket['department_id']));
                    echo htmlspecialchars($ticketDep); ?></p>
                <?php
                if (ticket::canModifyTicket($db, $username, $ticket)) {
                    ?>
                    <button class="edit"> &#9998;</button>
                    <form class="editForm" action="../actions/action_change_department.php" id="departmentChangeForm">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id'] ?>">
                        <label for="department"></label>
                        <select id="department" name="department">
                            <?php foreach ($departments as $department) {
                                $selected = ($department['id'] === $ticket['department_id']) ? 'selected' : ''; ?>
                                <option value="<?php echo htmlspecialchars($department['name']); ?>" <?php echo htmlspecialchars($selected); ?>><?php echo htmlspecialchars($department['name']); ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="submit" value="Submit">
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        $hashtags = ticket::getTicketHashtagNames($db, intval($ticket['id']));
        ?>
        <div class="editable" id="hashtagDivChange">
            <div class="hashtags" id="hashtagDiv">
                <?php
                foreach ($hashtags as $hashtag) {
                    ?>
                    <span class="tag" id="hashtagBox">
                        <?php echo htmlspecialchars('#' . $hashtag); ?>
                        </span>
                    <?php
                }
                ?>
            </div>
            <?php
            if (ticket::canModifyTicket($db, $username, $ticket)) {
                ?>
                <button class="edit" id="hashtagEdit"> &#9998;</button>
                <form class="editForm"
                      id="hashtagChangeForm">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['id'] ?>">
                    <label for="hashtagInput"></label>
                    <script src="../javascript/autocomplete.js" defer></script>
                    <input type="text" id="hashtagInput" name="hashtagInput" onkeyup="showResults(this.value)"
                           onclick="setHashtags([<?php foreach (Ticket::getAllHashtags($db) as $hashtag) {
                               echo "'" . $hashtag['name'] . "',";
                           } ?>]);">
                    <div id="result"></div>
                    <input type="submit" value="Submit">
                </form>
                <?php
            }
            ?>
        </div>
        <div class="fileDownload">
            <p>
                <?php
                foreach ($files as $file) {
                    ?>
                    <a href="<?php echo htmlspecialchars($file) ?>" target="_blank" rel="noopener noreferrer">
                        <img src="../images/download.png" alt="Download" width="50" height="50">
                    </a>
                    <?php
                }
                ?>
            </p>
        </div>
        <?php
        if ($ticket['status'] == 0 && ticket::canModifyTicket($db, $username, $ticket)) { ?>
            <script>
                document.getElementById('agentEdit').style.display = 'none';
            </script>
        <?php
        } elseif ($ticket['status'] == 1 && ticket::canModifyTicket($db, $username, $ticket)) { ?>
            <script>
                document.getElementById('agentEdit').style.display = 'block';
            </script>
        <?php
        } elseif (ticket::canModifyTicket($db, $username, $ticket)){
        ?>
            <script>
                document.querySelectorAll('.edit:not(#statusEdit,#deleteTicket)').forEach(button => {
                    button.style.display = 'none';
                });
            </script>
            <?php
        }
        ?>
    </div>
    <div class="ticketContainerBox" id="responseBox">
        <form action="../actions/action_add_response.php" id='responseForm'>
            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
            <input type="hidden" name="imgPath" value="<?php echo htmlspecialchars($pfpResponse); ?>">
            <input type="hidden" name="author_username"
                   value="<?php echo htmlspecialchars($session->getUsername()); ?>">
            <div class="contentBox">
                <fieldset>
                    <legend>Response</legend>
                    <label for="comment"></label>
                    <input list="faq" name="comment" id="comment" placeholder="Write your response here...">
                    <datalist id="faq"></datalist>
                </fieldset>
            </div>
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
            <input type="submit" value="Submit">
        </form>
        <label class="toggleB" id="response-logs" for="response-logs-checkbox">
            <input type="checkbox" id="response-logs-checkbox" name="response-logs-checkbox" value="response-logs">
            <span class="responseToggle" id="responseToggle">Response</span>
            <span class="logToggle" id="logToggle">Log</span>
        </label>
    </div>
    <?php
    if (intval($ticket['status']) == 2) {
        ?>
        <script>
            document.getElementById('responseForm').style.display = 'none';
        </script>
        <?php
    }
    ?>
    <div class="ticketContainerBox" id="responseDiv">
        <h2>Responses</h2>
        <?php
        $responses = ticket::getTicketResponses($db, intval($ticket['id']));
        if ($responses == null) {
            ?>
            <script>document.getElementById('responseDiv').style.display = 'none';</script>
            <?php
        }
        if (!empty($responses)) {
            foreach ($responses as $response) {
                ?>
                <div class="infoHeading">
                    <div class="authorInfo">
                        <img src="<?= user::getPfp($db, $response['username']) ?>" alt="User" width="50" height="50">
                        <h3><?php echo htmlspecialchars($response['username']) ?></h3>
                    </div>
                    <p><?php echo htmlspecialchars($response['date']); ?></p>
                </div>
                <div class="contentBox">
                    <fieldset>
                        <legend>Answer</legend>
                        <p><?php
                            if (strpos($response['content'], '#') !== false) {
                                $response['content'] = 'Answer: <a href="../pages/faq.php">' . $response['content'] . '</a>';
                            }
                            echo $response['content']; ?></p>
                    </fieldset>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="ticketContainerBox" id="logsDiv">
        <h2>Logs</h2>
        <ul class="log-list">
            <?php
            $logs = ticket::getLogs($db, intval($ticket['id']));
            if (!empty($logs)) {
                foreach ($logs as $log) {
                    ?>
                    <li class="log-item">
                        <p class="log-content"><?php echo htmlspecialchars($log['content']); ?></p>
                        <p class="log-date"><?php echo htmlspecialchars($log['date']); ?></p>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div>


<?php drawFooter(); ?>
