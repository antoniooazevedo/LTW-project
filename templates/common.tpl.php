<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/users.php');
require_once(__DIR__ . '/../database/connection.db.php');
?>

<?php function drawHeader(string $username){ 
    $db = getDatabaseConnection();
    $pfp = User::getPfp($db,$username);
    ?>
    <!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>Ticket Master</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
    <input type="checkbox" id="hamburger">
    <label for="hamburger" class="hamburger">
        <div class="bars" id="bar1"></div>
        <div class="bars" id="bar2"></div>
        <div class="bars" id="bar3"></div>
    </label>
    <header>
        <div class='logo'>
            <a href="../pages/index.php">
                <img src="../images/logo.svg" alt="Logo" width="50">
                <h1>Ticket Master</h1>
            </a>
        </div>
        <div class="options">
            <nav>
                <div class="links">
                    <ul>
                        <li><a href="../pages/ticketPage.php">Tickets</a></li>
                        <li><a href="/../pages/departments.php">Departments</a></li>
                        <li><a href="/../pages/agents.php">Team</a></li>
                        <li><a href="/../pages/faq.php">FAQ</a></li>
                        <li class="userProfile"><a href="../pages/profile.php">Account</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="user">
            <a class="userImage" href="../pages/profile.php">
                <img src="<?= htmlspecialchars($pfp) ?>" alt="User" width="50" height="50">
            </a>
        </div>
    </header>
    <main>
<?php } ?>

<?php function drawFooter()
{ ?>
    </main>

    <footer>
        <p>
            <a href="../pages/index.php">
                &copy; 2023 Ticket Master
            </a>
        </p>
    </footer>
    </body>
    </html>
<?php } ?>