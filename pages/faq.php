<?php
    declare(strict_types=1);

    require_once '../utils/session.php';
    require_once '../database/connection.db.php';
    require_once '../database/faq.php';
    require_once '../database/users.php';
    require_once '../templates/common.tpl.php';


    $session = new Session();
    $session->generateToken();

    if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

    $db = getDatabaseConnection();

    $faq = new FAQ();
    $result = $faq->getAllFAQ();

    $isAgent = User::isAgent($db, $session->getUsername());
?>

<html lang="en">  
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FAQ</title>
        <link rel="stylesheet" href="../css/faq.css">
    </head>
    <body>
        <?php drawHeader($session->getUsername()); ?>

        <main>
            <div id="faq-container">
                <h2>Frequently Asked Questions</h2>
                <?php
                    foreach ($result as $row) {
                        echo '<div class="faq-item">';
                        echo '<h3 class="faq-question">' . htmlspecialchars($row['question']) . '</h3>';
                        echo '<p class="faq-answer">' . htmlspecialchars($row['answer']) . '</p>';
                        echo '</div>';

                        if ($isAgent) {
                            echo '<div class="faq-buttons">';
                            echo '<form action="../actions/action_update_faq.php" method="post">';
                            echo '<input type="hidden" name="edit_id" value="' . $row['id'] . '">';
                            echo '<label for="question">Question</label>';
                            echo '<input type="text" name="question" id="question" value="' . htmlspecialchars($row['question']) . '" required>';
                            echo '<label for="answer">Answer</label>';
                            echo '<input type="text" name="answer" id="answer" value="' . htmlspecialchars($row['answer']) . '" required>';
                            echo '<input type="submit" value="&#9998;">';
                            echo '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
                            echo '</form>';
                            
                            echo '<form action="../actions/action_delete_faq.php" method="post">';
                            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                            echo '<input type="submit" value="&#128465;">';
                            echo '</form>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>

            <?php 
                if ($isAgent) {
                    echo '<section id="add-faq">';
                    echo '<h2>Add a new FAQ</h2>';
                    echo '<form action="../actions/action_add_faq.php" method="post">'; 
                    echo '<label for="question">Question</label>';
                    echo '<input type="text" name="question" id="question" required>';
                    echo '<label for="answer">Answer</label>';
                    echo '<input type="text" name="answer" id="answer" required>';
                    echo '<input type="submit" value="Add FAQ">';
                    echo '</form>';
                    echo '</section>';
                }
            ?>
        </main>

        <?php drawFooter(); ?>
    </body>
</html>