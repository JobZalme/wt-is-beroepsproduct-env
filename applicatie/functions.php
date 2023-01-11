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

function page_redirect() {
    if($_SERVER['PHP_SELF'] == '/addflight.php' || 
    $_SERVER['PHP_SELF'] == '/addpassenger.php' ||
    $_SERVER['PHP_SELF'] == '/succespage.php' ||
    $_SERVER['PHP_SELF'] == '/userpage.php')
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == false) {
            header('location: userchoice.php');
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

function getFlights($isAdmin, $where = '1 OR 1=1')
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

    
    if ($order == 'vluchthaven') {
        $sql = "SELECT * FROM Vlucht WHERE vluchtnummer = $where ORDER BY vluchtnummer";
    } else if ($order == 'vertrektijd') {
        $sql = "SELECT * FROM Vlucht WHERE vluchtnummer = $where ORDER BY vertrektijd";
    } else {
        $sql = "SELECT * FROM Vlucht WHERE vluchtnummer = $where";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($isAdmin == true) {
        foreach ($stmt as $row) {
            $data .= '<tr>';
            $data .= '<td>' . $row['vluchtnummer'] . '</td>';
            $data .= '<td>' . $row['bestemming'] . '</td>';
            $data .= '<td>' . $row['gatecode'] . '</td>';
            $data .= '<td>' . $row['max_aantal'] . '</td>';
            $data .= '<td>' . $row['max_gewicht_pp'] . '</td>';
            $data .= '<td>' . $row['max_totaalgewicht'] . '</td>';
            $data .= '<td>' . $row['vertrektijd'] . '</td>';
            $data .= '<td>' . $row['maatschappijcode'] . '</td>';

            $data .= '</tr>';
        }
        $data .= '</table';
    } else {
        foreach ($stmt as $row) {
            $data .= '<tr>';
            $data .= '<td>' . $row['vluchtnummer'] . '</td>';
            $data .= '<td>' . $row['bestemming'] . '</td>';
            $data .= '<td>' . $row['gatecode'] . '</td>';
            $data .= '<td>' . $row['vertrektijd'] . '</td>';
            $data .= '<td>' . $row['maatschappijcode'] . '</td>';

            $data .= '</tr>';
        }
        $data .= '</table';
    }
    return $data;
}


//----------------------------------------------------

function addFlight($destination, $gatecode, $max_amnt, $max_weight_pp, $max_totalweight, $departure_time, $firmcode)
{
    $db = maakVerbinding();
    $success = false;

    $curDate = getCurDate();
    $departure_time = formatDate($departure_time);

    $flightnumber = getMax('vluchtnummer', 'Vlucht') + 1;

    if ($departure_time >= $curDate) {
        // Insert flight data in database
        $stmt = $db->prepare('INSERT INTO Vlucht 
    (vluchtnummer,bestemming,gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, maatschappijcode) 
    VALUES 
    (:vluchtnummer, :bestemming, :gatecode, :max_aantal, :max_gewicht_pp, :max_totaalgewicht, :vertrektijd, :maatschappijcode);');

        $success = $stmt->execute([
            'vluchtnummer' => $flightnumber,
            'bestemming' => $destination,
            'gatecode' => $gatecode,
            'max_aantal' => $max_amnt,
            'max_gewicht_pp' => $max_weight_pp,
            'max_totaalgewicht' => $max_totalweight,
            'vertrektijd' => $departure_time,
            'maatschappijcode' => $firmcode,
        ]);
    }

    if ($success) {
        // Flight added successfully
        header('Location: succespage.php');
        exit;
    } else if ($departure_time < $curDate) {
        // Can't add flight if date is older than current date
        echo "Kan vlucht niet toevoegen als de datum ouder is als de huidige datum. (Ook op seconden)";
    } else {
        // Couldn't add flight
        echo "Er ging iets fout, kon vlucht niet toevoegen.";
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

    $result;
    foreach ($stmt as $rows) {
        $result = $rows[0];
    }
    return $result;
}

//----------------------------------------------------

function addPassenger($name, $flightnumber, $sex, $srvc_desk_number, $seat, $checkin_time)
{
    $db = maakVerbinding();
    $success = false;

    if (checkPersonLimit($flightnumber) > 0) {
        $checkin_time = formatDate($flightnumber);
        $passengernumber = getMax('passagiernummer', 'Passagier') + 1;

        // Insert flight data in database
        $stmt = $db->prepare('INSERT INTO Passagier 
    (passagiernummer,naam,vluchtnummer, geslacht, balienummer, stoel, inchecktijdstip) 
    VALUES 
    (:passagiernummer, :naam, :vluchtnummer, :geslacht, :balienummer, :stoel, :inchecktijdstip);');

        $success = $stmt->execute([
            'passagiernummer' => $passengernumber,
            'naam' => $name,
            'vluchtnummer' => $flightnumber,
            'geslacht' => $sex,
            'balienummer' => $srvc_desk_number,
            'stoel' => $seat,
            'inchecktijdstip' => $checkin_time,
        ]);

    }

        if ($success) {
            // Passenger added successfully
            header('Location: succespage.php');
            exit;
        } else {
            // Couldn't add passenger
            echo "Er ging iets fout, kon passagier niet toevoegen.";
        }
    }


//----------------------------------------------------

function addBag($passengernumber, $object_trackingcode, $weight)
{
    $db = maakVerbinding();
    $success = false;

    var_dump(checkCargoLimit($passengernumber));

    if (checkCargoLimit($passengernumber) > 0) {
        // Insert bag data in database
        $stmt = $db->prepare('INSERT INTO BagageObject 
    (passagiernummer, objectvolgnummer, gewicht) 
    VALUES 
    (:passagiernummer, :objectvolgnummer, :gewicht);');

        $success = $stmt->execute([
            'passagiernummer' => $passengernumber,
            'objectvolgnummer' => $object_trackingcode,
            'gewicht' => $weight,
        ]);
    }

    if ($success) {
        // Bag added successfully
        header('Location: succespage.php');
        exit;
    } else {
        // Couldn't add bag
        echo "Er ging iets fout, kon bagage niet toevogen.";
    }

}

function checkCargoLimit($passengernumber)
{
    $db = maakVerbinding();

    $sql = 'SELECT max_totaalgewicht - SUM(gewicht)
    FROM Vlucht v
    INNER JOIN Passagier p ON
                v.vluchtnummer = p.vluchtnummer
    INNER JOIN BagageObject bo ON
                p.passagiernummer = bo.passagiernummer
    WHERE v.vluchtnummer = (SELECT vluchtnummer
                            FROM Passagier
                            WHERE passagiernummer = :passagiernummer)
    GROUP BY max_totaalgewicht';

    $stmt = $db->prepare($sql);
    $stmt->execute(['passagiernummer' => $passengernumber]);

    $result = '';
    foreach ($stmt as $rows) {
        $result = $rows[0];
    }
    return $result;
}

function checkPersonLimit($flightnumber)
{
    $db = maakVerbinding();

    $sql = 'SELECT v.max_aantal - COUNT(p.vluchtnummer)
    FROM Vlucht v
    LEFT JOIN Passagier p ON
               v.vluchtnummer = p.vluchtnummer
    WHERE v.vluchtnummer = :vluchtnummer
    GROUP BY v.max_aantal, p.vluchtnummer'; 

    $stmt = $db->prepare($sql);
    $stmt->execute(['vluchtnummer' => $flightnumber]);

    $result = '';
    foreach ($stmt as $rows) {
        $result = $rows[0];
    }
    return $result;
}

?>