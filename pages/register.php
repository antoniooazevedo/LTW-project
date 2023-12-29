
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Ticket Master | Register</title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="../css/register.css">
        </head>
        <header>
            <img src="../images/logo.svg" alt="" width="400" height="400">
            <h1>Ticket Master</h1>
            <h3>World's number one ticketing solution for your company</h3>
        </header>
        <body>
            <form action="../actions/action_register.php" method="post">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">
                <label for="password2">Confirm Password</label>
                <input type="password" name="password2" id="password2" placeholder="Confirm your password">
                <br>
                <input type="submit" value="Register">
            </form>
            <p><?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 1){
                        echo "Username is already in use, please use another one";
                    } elseif ($_GET['error'] == 2) {
                        echo "Email is already in use, please use another one";
                    } elseif ($_GET['error'] == 3) {
                        echo "Passwords do not match, please try again";
                    } elseif ($_GET['error'] == 4) {
                        echo "Password must contain at least 8 characters, 1 uppercase letter, 1 lowercase letter and 1 number";
                    }elseif ($_GET['error'] == 5) {
                        echo "Invalid Username!";
                    }elseif ($_GET['error'] == 6) {
                        echo "Invalid Email!";
                    } elseif ($_GET['error'] == 7) {
                        echo "Invalid Name!";
                    }
                }
                ?></p>

            <a href="../pages/login.php">Login</a>
        </body>
    </html>
