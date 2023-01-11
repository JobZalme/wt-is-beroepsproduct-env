<?php
require_once('functions.php');
require_once('db_connectie.php');
session_start();
destroy_session();


$flightnumber = '';
$fi = false; 

//Make array for errors
$errors = [];

//Error message
$errmsg = '';

if (isset($_POST['vluchtnummer'])) {
  if (!empty($_POST['vluchtnummer'])) {
    $flightnumber = $_POST['vluchtnummer'];
    $fi = true; 
  } else {
    $errors[] = 'Er is geen vluchtnummer ingevoerd';
  }

  //If errors, display errors
  if (count($errors) > 0) {
    $errmsg .= '<ul>';
    foreach ($errors as $error) {
      $errmsg .= '<li>' . $error . '</li>';
    }
    $errmsg .= '</ul>';
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
  <title>Gelre Airport | Home</title>
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
      if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { 
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
      <form action="index.php" name="travelForm" method="POST">
        <h2>Vind een vlucht</h2>
        <?php echo $errmsg ?>
        <input type="number" name="vluchtnummer" id="vluchtnummer" placeholder="Vluchtnummer" pattern="[0-9]">
        <button type="submit" name="submit" class="button" id="submit">Zoeken</button>
        <?php
        if(isset($_POST['submit']) && $flightnumber > 0) {
          if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            echo getFlights(true, $flightnumber);
            } else {
            echo getFlights(false, $flightnumber);
            }
          }
          ?>

    </div>
    </form>
    </div>
  </main>
</body>

</html>