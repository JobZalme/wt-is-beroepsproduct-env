<?php
function login_redirect()
{
    if ($_SERVER['PHP_SELF'] == '/userchoice.php') {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            header('location: userpage.php');
        }
    }
}

//----------------------------------------------------

function destroy_session()
{
    if (isset($_POST['destroy_session'])) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}

//----------------------------------------------------

function getFlights($isAdmin, $waar = '1 OR 1=1')
{
    $conn = maakVerbinding();

    if ($isAdmin == true) {
        $data = '<table>
           <thead>
           <th>Vluchtnummer</th>
           <th>Bestemming</th>
           <th>Gatecode</th>
           <th>Max Aantal</th>
           <th>Max Gewicht</th>
           <th>Max Totaal Gewicht</th>
           <th>Vertrektijd</th>
           <th>Maatschappijcode</th>
           <th>
           <form action="flights.php" method="post">
           <p>Sorteren op: </p>
             <select name="order">
               <option value="vertrektijd">Vertrektijd</option>
               <option value="vluchthaven">Vluchthaven</option>
             </select>
             <button type="submit" class="button">Submit</button>
           </form>
           </th>
           ';

    } else {
        $data = '<table>
    <thead>
    <th>Vluchtnummer</th>
    <th>Bestemming</th>
    <th>Gatecode</th>
    <th>Vertrektijd</th>
    <th>Maatschappijcode</th>
    <th>
           <form action="flights.php" method="post">
           <p>Sorteren op: </p>
             <select name="order">
               <option value="vertrektijd">Vertrektijd</option>
               <option value="vluchthaven">Vluchthaven</option>
             </select>
             <button type="submit" class="button">Submit</button>
           </form>
           </th>
    ';
    }
    $data .= '</thead>';

    $order = '';
    if (isset($_POST['order'])) {
        $order = $_POST['order'];
    }

    //Hier geen placeholders gebruikt maar hoogstwaarschijnlijk wel handig om te doen!!!!
    if ($order == 'vluchthaven') {
        $sql = "SELECT * FROM Vlucht WHERE vluchtnummer = $waar ORDER BY vluchtnummer";
    } else if ($order == 'vertrektijd') {
        $sql = "SELECT * FROM Vlucht WHERE vluchtnummer = $waar ORDER BY vertrektijd";
    } else {
        $sql = "SELECT * FROM Vlucht WHERE vluchtnummer = $waar";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($isAdmin == true) {
        foreach ($stmt as $rij) {
            $data .= '<tr>';
            $data .= '<td>' . $rij['vluchtnummer'] . '</td>';
            $data .= '<td>' . $rij['bestemming'] . '</td>';
            $data .= '<td>' . $rij['gatecode'] . '</td>';
            $data .= '<td>' . $rij['max_aantal'] . '</td>';
            $data .= '<td>' . $rij['max_gewicht_pp'] . '</td>';
            $data .= '<td>' . $rij['max_totaalgewicht'] . '</td>';
            $data .= '<td>' . $rij['vertrektijd'] . '</td>';
            $data .= '<td>' . $rij['maatschappijcode'] . '</td>';

            $data .= '</tr>';
        }
        $data .= '</table';
    } else {
        foreach ($stmt as $rij) {
            $data .= '<tr>';
            $data .= '<td>' . $rij['vluchtnummer'] . '</td>';
            $data .= '<td>' . $rij['bestemming'] . '</td>';
            $data .= '<td>' . $rij['gatecode'] . '</td>';
            $data .= '<td>' . $rij['vertrektijd'] . '</td>';
            $data .= '<td>' . $rij['maatschappijcode'] . '</td>';

            $data .= '</tr>';
        }
        $data .= '</table';
    }
    return $data;
}


//----------------------------------------------------

function addFlight($bestemming, $gatecode, $max_aantal, $max_gewicht_pp, $max_totaalgewicht, $vertrektijd, $maatschappijcode)
{
    $db = maakVerbinding();
    $success = false;

    $curDate = getCurDate();
    $vertrektijd = formatDate($vertrektijd);

    $vluchtnummer = getMax('vluchtnummer', 'Vlucht') + 1;

    if ($vertrektijd >= $curDate) {
        // Insert flight data in database
        $stmt = $db->prepare('INSERT INTO Vlucht 
    (vluchtnummer,bestemming,gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, maatschappijcode) 
    VALUES 
    (:vluchtnummer, :bestemming, :gatecode, :max_aantal, :max_gewicht_pp, :max_totaalgewicht, :vertrektijd, :maatschappijcode);');

        $success = $stmt->execute([
            'vluchtnummer' => $vluchtnummer,
            'bestemming' => $bestemming,
            'gatecode' => $gatecode,
            'max_aantal' => $max_aantal,
            'max_gewicht_pp' => $max_gewicht_pp,
            'max_totaalgewicht' => $max_totaalgewicht,
            'vertrektijd' => $vertrektijd,
            'maatschappijcode' => $maatschappijcode,
        ]);
    }

    if ($success) {
        // Flight added successfully
        header('Location: succespage.php');
        exit;
    } else if ($vertrektijd < $curDate) {
        // Can't add flight if date is older than current date
        echo "Can't add flight if date is older than current date";
    } else {
        // Couldn't add flight
        echo "Something went wrong. Flight could not be added.";
    }
}

//----------------------------------------------------

function getCurDate()
{
    $system_date = new DateTime('Now', new DateTimeZone('CET'));
    $system_date = $system_date->format('Y/m/d H:i:s.000');
    return $system_date;
}

//----------------------------------------------------

function formatDate($date)
{
    $date = strtotime($date);
    $date = new DateTime("@$date");
    $date = $date->format('Y/m/d H:i:s.000');
    return $date;
}

//----------------------------------------------------

function getMax($row, $table)
{
    require_once('db_connectie.php');
    $db = maakVerbinding();

    $stmt = $db->prepare("SELECT MAX($row) FROM $table");
    $stmt->execute();

    $resultaat;
    foreach ($stmt as $rij) {
        $resultaat = $rij[0];
    }
    return $resultaat;
}

//----------------------------------------------------

function addPassenger($naam, $vluchtnummer, $geslacht, $balienummer, $stoel, $inchecktijdstip)
{
    $db = maakVerbinding();
    $success = false;

    if (checkPersonLimit($vluchtnummer) > 0) {
        $inchecktijdstip = formatDate($inchecktijdstip);
        $passagiernummer = getMax('passagiernummer', 'Passagier') + 1;

        // Insert flight data in database
        $stmt = $db->prepare('INSERT INTO Passagier 
    (passagiernummer,naam,vluchtnummer, geslacht, balienummer, stoel, inchecktijdstip) 
    VALUES 
    (:passagiernummer, :naam, :vluchtnummer, :geslacht, :balienummer, :stoel, :inchecktijdstip);');

        $success = $stmt->execute([
            'passagiernummer' => $passagiernummer,
            'naam' => $naam,
            'vluchtnummer' => $vluchtnummer,
            'geslacht' => $geslacht,
            'balienummer' => $balienummer,
            'stoel' => $stoel,
            'inchecktijdstip' => $inchecktijdstip,
        ]);

    }

        if ($success) {
            // Passenger added successfully
            header('Location: succespage.php');
            exit;
        } else {
            // Couldn't add passenger
            echo "Something went wrong. Passenger could not be added.";
        }
    }


//----------------------------------------------------

function addBag($passagiernummer, $objectvolgnummer, $gewicht)
{
    $db = maakVerbinding();

    // Insert bag data in database
    $stmt = $db->prepare('INSERT INTO BagageObject 
    (passagiernummer, objectvolgnummer, gewicht) 
    VALUES 
    (:passagiernummer, :objectvolgnummer, :gewicht);');

    $success = $stmt->execute([
        'passagiernummer' => $passagiernummer,
        'objectvolgnummer' => $objectvolgnummer,
        'gewicht' => $gewicht,
    ]);

    if ($success) {
        // Bag added successfully
        header('Location: succespage.php');
        exit;
    } else {
        // Couldn't add bag
        echo "Something went wrong. Bag could not be added.";
    }

}

function getCargoLimit()
{
    $db = maakVerbinding();

    $stmt = $db->prepare('SELECT max_aantal FROM Vlucht WHERE vluchtnummer = 1 OR 1=1);');
    $stmt->execute();

    return $stmt;
}

function checkPersonLimit($vluchtnummer)
{
    $db = maakVerbinding();

    $sql = '
    SELECT v.max_aantal - COUNT(p.vluchtnummer)
    FROM Vlucht v
    LEFT JOIN Passagier p ON
               v.vluchtnummer = p.vluchtnummer
    WHERE v.vluchtnummer = :vluchtnummer
    GROUP BY v.max_aantal, p.vluchtnummer'; 
    $stmt = $db->prepare($sql);
    $stmt->execute(['vluchtnummer' => $vluchtnummer]);

    $resultaat = '';
    foreach ($stmt as $rij) {
        $resultaat = $rij[0];
    }
    return $resultaat;
}

?>