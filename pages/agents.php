<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/users.php');
require_once(__DIR__ . '/../utils/misc.php');

$db = getDatabaseConnection();
$session = new Session();
$session->generateToken();

if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

$username = $session->getUsername();

if ($username == null) die(header('Location: /../pages/login.php'));


drawHeader($username);

$agents = user::getAgentsInfo($db);
?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../javascript/showHideForm.js" defer></script>
        <link rel="stylesheet" href="../css/agents.css">
        <title>Team</title></head>
    <h1>Our Team</h1>
    <table>
        <tr>
            <th></th>
            <th>Username</th>
            <th>Name</th>
            <th>Role</th>
        </tr>
        <?php foreach ($agents as $agent) { ?>
            <tr>
                <td><img src="<?= $agent['image_url'] ?>" alt="Agent image" width=50 height=50></td>
                <td><?= htmlspecialchars($agent['username']) ?></td>
                <td><?= htmlspecialchars($agent['name']);
                    ?></td>
                <td><?php if (user::isAdmin($db, $agent['username'])) {
                        echo "Admin";
                    } else {
                        echo "Agent";
                    } ?></td>
            </tr>
        <?php } ?>
    </table>

<?php
if (user::isAdmin($db, $username)) {

    ?>
    <div class="forms">
        <button type="button" id="showAgForm" name="showAgForm">Promote/Demote Users</button>
        <button type="button" id="showAssignDepForm" name="showAssignDepForm">Assign/Unassign Agent to Department</button>
        
        <form id="modifyUsers" method="POST" action="../actions/action_prom_dem_users.php">
            <input type="text" name="username" placeholder="Enter Username">
            <button name="add-rm" id="promote-demote" type="button">Promote</button>
            <input type="hidden" id="action_input" name="action" value="promote">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <input type="submit" value="Submit">
        </form>

        <form id="modifyUserDeps" method="POST" action="../actions/action_assign_users.php">
            <input type="text" name="username" placeholder="Enter Username">
            <select name="department">
            <?php
                foreach (Misc::getDepartments($db) as $department){
                    echo "<option value=";
                    echo $department['id'];
                    echo ">";
                    echo htmlspecialchars($department['name']);
                    echo "</option>";
                }
            ?>
            </select>
            <button name="add-rm" id="assign-unassign" type="button">Assign</button>
            <input type="hidden" id="action_input_assign" name="action" value="assign">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <input type="submit" value="Submit">
        </form>
    </div>
    <?php
}
?>


<?php drawFooter(); ?>