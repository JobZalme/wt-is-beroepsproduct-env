<?php
require_once('functions.php'); 
require_once('db_connectie.php');
session_start();
destroy_session(); 
page_redirect(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Gelre Airport | Vluchten</title>
</head>

<body>

    <nav>
        <ul>
            <li><a href="userchoice.php"><img src="images/User.svg" alt="User icon"></a></li>
            <li><a href="index.php"><img src="images/fi-rr-home.svg" alt="Home icon"></a></li>
            <li><a href="flights.php"><img src="images/fi-rr-plane-alt.svg" alt="Vluchten icon"></a></li>
            <li><a href="privacypolicy.php"><img src="images/fi-rr-info.svg" alt="Contact icon"></a></li>
            <li class="push"><a href="#"><img src="images/fi-rr-settings-sliders.svg" alt="Theme picker icon"></a></li>
            <?php
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                echo '<li>
            <form method="post">
            <button type="submit" name="destroy_session"><img src="images/fi-rr-exit.svg" alt="Logout"></button>
            </form>
            </li>';
            }
            ?>
        </ul>
    </nav>
    <div class="container">
        <form action="userpage.php">
            <h2>Actie succesvol!</h2>
            <button class="button">Terug naar het overzicht</button>
        </form>
    </div>
</body>
</html>