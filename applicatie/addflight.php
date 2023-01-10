<?php
require_once('functions.php');
require_once 'db_connectie.php';
session_start();
destroy_session();

//Make variables
$bestemming = '';
$gatecode = '';
$max_aantal = '';
$max_gewicht_pp = '';
$max_totaalgewicht = '';
$vertrektijd = '';
$maatschappijcode = '';


//Make array for errors
$errors = [];

//Error message
$errmsg = '';

if (isset($_POST['bestemming']) && isset($_POST['gatecode']) && isset($_POST['max_aantal']) && isset($_POST['max_gewicht_pp'])
    && isset($_POST['max_totaalgewicht']) && isset($_POST['vertrektijd']) && isset($_POST['maatschappijcode'])) {
    if (!empty($_POST['bestemming'])) {
        $bestemming = $_POST['bestemming'];
    } else {
        $errors[] = 'Er is geen bestemming ingevoerd';
    }

    if (!empty($_POST['gatecode'])) {
        $gatecode = $_POST['gatecode'];
    } else {
        $errors[] = 'Er is geen gatecode ingevoerd';
    }

    if (!empty($_POST['max_aantal'])) {
        $max_aantal = $_POST['max_aantal'];
    } else {
        $errors[] = 'Er is geen max_aantal ingevoerd';
    }

    if (!empty($_POST['max_gewicht_pp'])) {
        $max_gewicht_pp = $_POST['max_gewicht_pp'];
    } else {
        $errors[] = 'Er is geen max_gewicht_pp ingevoerd';
    }

    if (!empty($_POST['max_totaalgewicht'])) {
        $max_totaalgewicht = $_POST['max_totaalgewicht'];
    } else {
        $errors[] = 'Er is geen max_totaalgewicht ingevoerd';
    }

    if (!empty($_POST['vertrektijd'])) {
        $vertrektijd = $_POST['vertrektijd'];
    } else {
        $errors[] = 'Er is geen vertrektijd ingevoerd';
    }

    if (!empty($_POST['maatschappijcode'])) {
        $maatschappijcode = $_POST['maatschappijcode'];
    } else {
        $errors[] = 'Er is geen maatschappijcode ingevoerd';
    }

    //If errors, display errors
    if (count($errors) > 0) {
        $errmsg .= '<ul>';
        foreach ($errors as $error) {
            $errmsg .= '<li>' . $error . '</li>';
        }
        $errmsg .= '</ul>';
        //Add flight connection
    } else {
        addFlight($bestemming, $gatecode, $max_aantal, $max_gewicht_pp, $max_totaalgewicht, $vertrektijd, $maatschappijcode);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Gelre Airport | Vlucht toevoegen</title>
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
    <main>
        <div class="container">
            <form action="addflight.php" method="POST" id="addflight">
                <h2>Vlucht toevoegen</h2>
                <!-- Display error message  -->
                <?= $errmsg ?>
                <label for="bestemming">Bestemming: </label>
                <input type="text" name="bestemming" id="bestemming">
                <br>
                <label for="gatecode">Gatecode: </label>
                <input type="text" name="gatecode" id="gatecode">
                <br>
                <label for="max_aantal">Max. Aantal: </label>
                <input type="number" name="max_aantal" id="max_aantal">
                <br>
                <label for="max_gewicht_pp">Max. Gewicht P.P.: </label>
                <input type="number" name="max_gewicht_pp" id="max_gewicht_pp">
                <br>
                <label for="max_totaalgewicht">Max. Gewicht: </label>
                <input type="number" name="max_totaalgewicht" id="max_totaalgewicht">
                <br>
                <label for="vertrektijd">Vertrektijd: </label>
                <input type="datetime-local" name="vertrektijd" id="vertrektijd">
                <br>
                <label for="maatschappijcode">Maatschappijcode: </label>
                <input type="text" name="maatschappijcode" id="maatschappijcode">
                <button type="submit" class="button" id="submit">Versturen</button>
            </form>
        </div>
    </main>
</body>

</html>