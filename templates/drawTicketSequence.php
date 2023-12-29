<?php

function drawNavBarTicket()
{
    ?>

    <nav id="navBarTicket">
        <ul>
            <li><a href="../pages/create_ticket.php">Create Ticket</a></li>
            <li><a href="../pages/ticketPage.php">Your tickets</a></li>
        </ul>
    </nav>
    <?php
}

function drawTicketSequence(array $tickets, PDO $db)
{
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'dateC') {
            usort($tickets, function ($a, $b) {
                return $a['date'] <=> $b['date'];
            });
        } else if ($_GET['sort'] == 'priorityC') {
            usort($tickets, function ($a, $b) {
                return $a['priority'] <=> $b['priority'];
            });
        } else if ($_GET['sort'] == 'priorityD') {
            usort($tickets, function ($a, $b) {
                return $b['priority'] <=> $a['priority'];
            });
        } else if ($_GET['sort'] == 'dateD') {
            usort($tickets, function ($a, $b) {
                return $b['date'] <=> $a['date'];
            });
        }
    }
    if (!empty($tickets)) {
        $statusFilter = $_GET['status'] ?? null;
        $departmentFilter = $_GET['department'] ?? null;
        $hashtagFilter = $_GET['hashtag'] ?? null;
        $agentFilter = $_GET['agent'] ?? null;
        $priorityFilter = $_GET['priority'] ?? null;
        foreach ($tickets as $ticket) {
            if (($statusFilter != null && $ticket['status'] != $statusFilter) || ($departmentFilter != null && $ticket['department_id'] != $departmentFilter) || ($hashtagFilter != null && !ticket::ticketHasHashtag($db, $ticket['id'], $hashtagFilter)) || ($agentFilter != null && $ticket['agent_username'] != $agentFilter) || ($priorityFilter != null && $ticket['priority'] != $priorityFilter)) {
                continue;
            }
            ?>
            <a href="../pages/ticket_details.php?id=<?php echo htmlspecialchars($ticket['id']) ?>" class="refToTicketDetail">
                <div class="ticket">
                    <h2><?php echo htmlspecialchars($ticket['subject']); ?></h2>
                    <p><?php echo htmlspecialchars(ticket::getStatusName($db, $ticket['status'])); ?></p>
                    <p>Assigned agent: <?php echo htmlspecialchars($ticket['agent_username']); ?></p>
                    <p>Date created: <?php echo htmlspecialchars($ticket['date']); ?></p>
                    <p>Department: <?php echo htmlspecialchars(ticket::getDepartmentName($db, $ticket['department_id'])); ?></p>
                    <?php
                    if (user::isAgent($db, $_SESSION['username'])) {
                        ?>
                        <p>Priority: <?php echo htmlspecialchars(ticket::getPriorityName($db, $ticket['priority'])); ?></p>
                        <?php
                    }
                    ?>
                    <p><?php echo 'Content: ' . htmlspecialchars($ticket['content']); ?></p>
                    <?php
                    $hashtags = ticket::getTicketHashtagNames($db, $ticket['id']);
                    if (!empty($hashtags)) {
                        ?>
                        <div class="hashtags">
                            <p>Hashtags:</p>
                            <?php
                            foreach ($hashtags as $hashtag) {
                                ?>
                                <span><?php echo '#' . htmlspecialchars($hashtag); ?></span>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </a>
            <?php
        }
    } else {
        ?>
        <div class="createATicketButton">
            <a href="../pages/create_ticket.php">
                Create a ticket
            </a>
        </div>

        <?php
    }
} ?>

<?php
function drawFilter(array $statuses, array $departments, array $hashtags, array $agents, array $priorities)
{
    ?>
    <form method="get" id ="filterForm">
        <label for="status">Filter by status:</label>
        <select id="status" name="status">
            <option value="" <?php if (!isset($_GET['status'])) echo 'selected'; ?>>All</option>
            <?php
            foreach ($statuses as $status) {
                ?>
                <option value="<?php echo $status['id']; ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $status['id']) echo 'selected'; ?>><?php echo htmlspecialchars($status['name']); ?></option>
                <?php
            }
            ?>
        </select>
        <label for="priority">Filter by priority:</label>
        <select id="priority" name="priority">
            <option value="" <?php if (!isset($_GET['priority'])) echo 'selected'; ?>>All</option>
            <?php
            foreach ($priorities as $priority) {
                ?>
                <option value="<?php echo $priority['id']; ?>" <?php if (isset($_GET['priority']) && $_GET['priority'] == $priority['id']) echo 'selected'; ?>><?php echo htmlspecialchars($priority['name']); ?></option>
                <?php
            }
            ?>
        </select>
        <label for="department">Filter by department:</label>
        <select id="department" name="department">
            <option value="" <?php if (!isset($_GET['department'])) echo 'selected'; ?>>All</option>
            <?php
            foreach ($departments as $department) {
                ?>
                <option value="<?php echo $department['id']; ?>" <?php if (isset($_GET['department']) && $_GET['department'] == $department['id']) echo 'selected'; ?>><?php echo htmlspecialchars($department['name']); ?></option>
                <?php
            }
            ?>
        </select>
        <label for="agent">Filter by agent:</label>
        <select id="agent" name="agent">
            <option value="" <?php if (!isset($_GET['agent'])) echo 'selected'; ?>>All</option>
            <?php
            foreach ($agents as $agent) {
                ?>
                <option value="<?php echo htmlspecialchars($agent['agent_username']); ?>" <?php if (isset($_GET['agent']) && $_GET['agent'] == $agent['agent_username']) echo 'selected'; ?>><?php echo htmlspecialchars($agent['agent_username']); ?></option>
                <?php
            }
            ?>
        </select>
        <label for="hashtag">Filter by hashtags:</label>
        <select id="hashtag" name="hashtag">
            <option value="" <?php if (!isset($_GET['hashtag'])) echo 'selected'; ?>>All</option>
            <?php
            foreach ($hashtags as $hashtag) {
                ?>
                <option value="<?php echo $hashtag['name']; ?>" <?php if (isset($_GET['hashtag']) && $_GET['hashtag'] == $hashtag['name']) echo 'selected'; ?>><?php echo htmlspecialchars($hashtag['name']); ?></option>
                <?php
            }
            ?>
        </select>
        <input type="submit" value="Apply Filter">
    </form>
    <form method="get" id ="sortForm">
        <label for="sort">Sort by:</label>
        <select id="sort" name="sort">
            <option value="" <?php if (!isset($_GET['sort'])) echo 'selected'; ?>>Id</option>
            <option value="dateC" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'dateC') echo 'selected'; ?>>Date
                &uarr;
            </option>
            <option value="priorityC" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'priorityC') echo 'selected'; ?>>
                Priority &uarr;
            </option>
            <option value="dateD" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'dateD') echo 'selected'; ?>>Date
                &darr;
            </option>
            <option value="priorityD" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'priorityD') echo 'selected'; ?>>
                Priority &darr;
            </option>
        </select>
        <input type="submit" value="Sort">
    </form>


    <?php
}

?>
