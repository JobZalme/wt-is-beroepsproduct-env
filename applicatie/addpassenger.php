<?php
require_once('functions.php');
require_once 'db_connectie.php';
session_start();
destroy_session();

//Make variables
$naam = '';
$vluchtnummer = '';
$geslacht = '';
$balienummer = '';
$stoel = '';
$inchecktijdstip = '';

//Make array for errors
$errors = [];

//Error message
$errmsg = '';

if (isset($_POST['naam']) && isset($_POST['vluchtnummer']) && isset($_POST['geslacht']) && isset($_POST['balienummer']) 
    && isset($_POST['stoel']) && isset($_POST['inchecktijdstip'])) {
    if (!empty($_POST['naam'])) {
        $naam = $_POST['naam'];
    } else {
        $errors[] = 'Er is geen naam ingevoerd';
    }

    if (!empty($_POST['vluchtnummer'])) {
        $vluchtnummer = $_POST['vluchtnummer'];
    } else {
        $errors[] = 'Er is geen vluchtnummer ingevoerd';
    }

    if (!empty($_POST['geslacht'])) {
        $geslacht = $_POST['geslacht'];
    } else {
        $errors[] = 'Er is geen geslacht ingevoerd';
    }

    if (!empty($_POST['balienummer'])) {
        $balienummer = $_POST['balienummer'];
    } else {
        $errors[] = 'Er is geen balienummer ingevoerd';
    }

    if (!empty($_POST['stoel'])) {
        $stoel = $_POST['stoel'];
    } else {
        $errors[] = 'Er is geen stoel ingevoerd';
    }

    if (!empty($_POST['inchecktijdstip'])) {
        $inchecktijdstip = $_POST['inchecktijdstip'];
    } else {
        $errors[] = 'Er is geen inchecktijdstip ingevoerd';
    }

    //If errors, display errors
    if (count($errors) > 0) {
        $errmsg .= '<ul>';
        foreach ($errors as $error) {
            $errmsg .= '<li>' . $error . '</li>';
        }
        $errmsg .= '</ul>';
        //Add passenger
    } else {
        addPassenger($naam,$vluchtnummer, $geslacht, $balienummer, $stoel, $inchecktijdstip); 
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
            <form action="addpassenger.php" method="POST" id="flightoptions">
                <h2>Passagier toevoegen</h2>
                <label for="naam">Naam: </label>
                <input type="text" name="naam" id="naam" pattern="[A-Za-z].{2,}">
                <br>
                <label for="vluchtnummer">Vluchtnummer: </label>
                <input type="number" name="vluchtnummer" id="vluchtnummer">
                <br>
                <label for="geslacht">Geslacht: </label>
                <select name="geslacht" id="geslacht">
                    <option value="M">M</option>
                    <option value="V">V</option>
                    <option value="X">X</option>
                </select>
                <br>
                <label for="balienummer">Balienummer:</label>
                <input type="number" name="balienummer" id="balienummer">
                <br>
                <label for="stoel">Stoel:</label>
                <input type="text" name="stoel" id="stoel">
                <br>
                <label for="inchecktijdstip">Inchecktijdstip:</label>
                <input type="datetime-local" name="inchecktijdstip" id="inchecktijdstip">
                <button type="submit" class="extrabuttonprops button">Toevoegen</button>
            </form>
        </div>
    </main>
</body>
</html>