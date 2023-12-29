<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/ticketPrev.tpl.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/misc.php');

$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

$db = getDatabaseConnection();
$pfp = User::getPfp($db, $session->getUsername());
$tickets = ticket::getTickets($db, $session->getUsername());
$username = $session->getUsername();
 ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/userP.css">
        <link rel="stylesheet" href="../css/cards.css">
        <script src="../javascript/profile.js" defer></script>
        <script src="../javascript/scripts.js" defer></script>
    </head>
    <?php drawHeader($username);
    if(isset($_GET['error']) && $_GET['error'] == 'invalid_file_type') echo "<script>alert('Uploaded file type is not supported for this operation');</script>";?>
    <div class="profileContainer" id="profilePage">
        <div class="settingsColumn">
            <div class="profileCard">
                <div class="cardBody">
                <form action="/../actions/action_upload_pfp.php" method="post" enctype="multipart/form-data">
                        <label for="pfp"><img src="<?= $pfp ?>" alt="userImg" class="userImg"></label>
                        <input type="file" id="pfp" name="pfp" required="required" accept="image/*">
                        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                        <input type="submit" id="submit" value="Submit changes!">
                    </form>
                    <h5 class="usernameP">
                        <?php
                        echo htmlspecialchars($username);
                        ?>
                    </h5>
                </div>
            </div>
            <div class="profileCard" id="settingsCard">
                <div class="optionsBody">
                    <ul class="userSettings">
                        <li class="button">
                            <button class="openButton" onclick="openEmailForm()">Change email</button>
                        </li>
                        <li class="button">
                            <button class="openButton" onclick="openPswForm()">Change password</button>
                        <li class="button">
                            <button class="openButton" onclick="openInfoForm()">Change Name</button>
                        <li class="button">
                            <button class="openButton" onclick="openUserNameForm()">Change Username</button>
                        <li class="button">
                            <button class="openButton" onclick="window.location.href = '../actions/action_logout.php'">
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="userInfoColumn">
            <div class="profileCard" id="profileInfoCard">
                <div class="cardBody">
                    <div class="row">
                        <div class="titleColumn">
                            <p class="title">Name</p>
                        </div>
                        <div class="infoColumn">
                            <p class="info" id="user_name">
                                <?php
                                echo htmlspecialchars(User::getName($db, $username));
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="titleColumn">
                            <p class="title">Email</p>
                        </div>
                        <div class="infoColumn">
                            <p class="info" id ="userEmail">
                                <?php
                                echo htmlspecialchars(User::getUserEmail($db, $username));
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="previewTickets">
                <?php drawTicketPreview($db, $tickets) ?>
            </div>
        </div>
    </div>
    <div class="changeInfoB" id="popupEmail">
        <form action="../actions/action_change_email.php" id ="emailForm" method="post">
            <div class="inputB">
                <label for="email">New e-mail</label>
                <input type="email" placeholder="Enter Email" name="email" id="email" required>
            </div>
            <button type="submit" class="btn submit">Submit</button>
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
    </div>

    <div class="changeInfoB" id="popupPsw">
        <form action="../actions/change_password.php" method="post">
            <div class="inputB">
                <label for="psw">New password</label>
                <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
            </div>
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <button type="submit" class="btn submit">Submit</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
    </div>

    <div class="changeInfoB" id="popupName">
        <form action="../actions/action_change_name.php" id ="nameForm">
            <div class="inputB">
                <label for="name">New name</label>
                <input type="text" placeholder="Enter Name" name="name" id="name" required>
            </div>
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <button type="submit" class="btn submit">Submit</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
    </div>
    <div class="changeInfoB" id="popupUserName">
        <form action="../actions/action_change_username.php" method="post">
            <div class="inputB">
                <label for="username">New username</label>
                <input type="text" placeholder="Enter Username" name="username" id="username" required>
            </div>
            <span id="username_error">
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 5) {
                    echo "Insert a different name!";
                } elseif (isset($_GET['error']) && $_GET['error'] == 6) {
                    echo "Username already in use!";
                } elseif (isset($_GET['error']) && $_GET['error'] == 7) {
                    echo "Invalid Username!";
                }
                ?>
            </span>
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <button type="submit" class="btn submit">Submit</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
    </div>
<?php
drawFooter();
?>