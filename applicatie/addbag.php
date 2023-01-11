<?php
require_once('functions.php');
require_once 'db_connectie.php';
session_start();
destroy_session();

//Make variables
$passengernumber = '';
$object_trackingcode = '';
$weight = '';

//Make array for errors
$errors = [];

//Error message
$errmsg = '';

if (isset($_POST['passagiernummer']) && isset($_POST['objectvolgnummer']) && isset($_POST['gewicht'])) {
    if (!empty($_POST['passagiernummer'])) {
        $passengernumber = $_POST['passagiernummer'];
    } else {
        $errors[] = 'Er is geen passagiernummer ingevoerd';
    }

    if (!empty($_POST['objectvolgnummer'])) {
        $object_trackingcode = $_POST['objectvolgnummer'];
    } else {
        $errors[] = 'Voer een getal groter dan 0 in';
    }

    if (!empty($_POST['gewicht'])) {
        $weight = $_POST['gewicht'];
    } else {
        $errors[] = 'Er is geen gewicht ingevoerd';
    }

    //If errors, display errors
    if (count($errors) > 0) {
        $errmsg .= '<ul>';
        foreach ($errors as $error) {
            $errmsg .= '<li>' . $error . '</li>';
        }
        $errmsg .= '</ul>';
        //Add bag
    } else {
        addBag($passengernumber, $object_trackingcode, $weight);
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
    <title>Gelre Airport | Bagage toevoegen</title>
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
            <form action="addbag.php" method="POST">
                <h2>Bagage toevoegen</h2>
                <!-- Display error message  -->
                <?= $errmsg ?>
                <label for="passagiernummer">Passagiernr: </label>
                <input type="number" name="passagiernummer" id="passagiernummer">
                <br>
                <label for="objectvolgnummer">Objectvolgnr: </label>
                <input type="number" name="objectvolgnummer" id="objectvolgnummer">
                <br>
                <label for="gewicht">Gewicht: </label>
                <input type="number" step="0.01" name="gewicht" id="gewicht">
                <button type="submit" class="button" id="submit">Versturen</button>
            </form>
        </div>
    </main>
</body>

</html>