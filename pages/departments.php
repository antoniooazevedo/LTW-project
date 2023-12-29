<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../database/ticket.php');

    $db = getDatabaseConnection();
    $session = new Session();
    $session->generateToken();
    
    if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));
    drawHeader($session->getUsername());

    $departments = ticket::getAllDepartments($db);
    $link_departments = ticket::getAllFromLinkDepartment($db);

?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/departments.css">
        <script src="../javascript/showHideForm.js" defer></script>
        <title>Departments</title>
    </head>
    <h1>Departments</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Number of agents assigned</th>
        </tr>
        <?php foreach ($departments as $department) { 
                $num_agents = 0;
            ?>
            <tr>
                <td><?= htmlspecialchars($department['name']) ?></td>
                <td><?= $department['id'] ?></td>
                <?php foreach ($link_departments as $link_department) { 
                     if ($link_department['department_id'] == $department['id']) { $num_agents++; } }?>
                <td><?= $num_agents ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php if (user::isAdmin($db, $session->getUsername())) { ?>
    <button type="button" id="showDepForm" name="showDepForm">Add/Remove Departments</button>
        <form id="modifyDeps" method="POST" action="../actions/action_add_remove_department.php">
            <input type="text" name="department" placeholder="Enter Department Name">
            <button name="add-rm" id="add-rm" type="button">Add</button> 
            <input type="hidden" id="action_input" name="action" value="add">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <input type="submit" value="Submit">
        </form>
    <?php } ?>

<?php
    drawFooter();
?>