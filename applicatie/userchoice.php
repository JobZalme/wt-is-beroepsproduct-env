<?php
require_once('functions.php');
session_start(); 
login_redirect(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/stylesheet.css">
  <link rel="stylesheet" href="css/normalize.css">
  <title>Gelre Airport | Login</title>
</head>

<body>

  <nav>
    <ul>
      <li><a href="userchoice.php"><img src="images/User.svg" alt="User icon"></a></li>
      <li><a href="index.php"><img src="images/fi-rr-home.svg" alt="Home icon"></a></li>
      <li><a href="flights.php"><img src="images/fi-rr-plane-alt.svg" alt="Vluchten icon"></a></li>
      <li><a href="privacypolicy.php"><img src="images/fi-rr-info.svg" alt="Contact icon"></a></li>
      <li class="push"><a href="#"><img src="images/fi-rr-settings-sliders.svg" alt="Theme picker icon"></a></li>
      <!-- <li><a href="index.html"><img src="images/fi-rr-exit.svg" alt="Logout icon"></a></li> -->
    </ul>
  </nav>

  <main>
    <div class="container">
      <form action="#">
        <h2>Wat wilt u doen?</h2>
        <button class="button" formaction="loginuser.php">Inloggen <br> Medewerker</button>
        <button class="button" formaction="addbag.php">Bagage <br> toevoegen</button>
      </form>
    </div>
  </main>
</body>