<?php
// Create database connection
require_once 'db_connectie.php';
session_start();
$_SESSION['logged_in'] = false; 

//Make variables
$username = '';
$password = '';


//Make array for errors
$errors = [];

//Error message
$errmsg = '';

//If username not empty, set variable. If username empty, add error to error array. 
if (isset($_POST['gebruikersnaam']) && isset($_POST['wachtwoord'])) {
  if (!empty($_POST['gebruikersnaam'])) {
    htmlspecialchars($username); 
    $username = $_POST['gebruikersnaam'];
  } else {
    $errors[] = 'Er is geen gebruikersnaam ingevoerd';
  }

  //If password not empty, set variable. If password empty, add error to error array. 
  if (!empty($_POST['wachtwoord'])) {
    htmlspecialchars($password); 
    $password = $_POST['wachtwoord'];
  } else {
    $errors[] = 'Er is geen wachtwoord ingevoerd';
  }

  //If errors, display errors
  if (count($errors) > 0) {
    $errmsg .= '<ul>';
    foreach ($errors as $error) {
      $errmsg .= '<li>' . $error . '</li>';
    }
    $errmsg .= '</ul>';
    //Make db connection
  } else {
    $db = maakVerbinding();

    $salt = 'iusdfhlasdfjhasdjkfnacLJJLKDFKABDJSJKHLNlfjhdlfhasduhflILHDFJOIFuiejorubq789';
    $password .= $salt; 

    // Check if the username and password are correct in the database
    $stmt = $db->prepare('SELECT password FROM medewerkers WHERE naam = :gebruikersnaam');
    $stmt->execute(['gebruikersnaam' => $username]);

    $hash = '';
    foreach($stmt as $row) {
      $hash = $row[0]; 
    }
    $result = password_verify($password, $hash);

    if ($result) {
      // Login successful
      $_SESSION['logged_in'] = true;
      header('Location: userpage.php');
      exit;
    } else {
      // Login failed
      $_SESSION['logged_in'] = false;
      $errmsg = '<ul><li>Ongeldige gebruikersnaam of wachtwoord</li></ul>';
    }
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
    </ul>
  </nav>

  <main>
    <div class="container">
      <form action="loginuser.php" name="loginForm" method="post">
        <h2>Login Medewerker</h2>
        <!-- Display error message  -->
        <?= $errmsg ?>
        <div class="loginForm">
          <label for="gebruikersnaam" id="usernameSVG">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 35.021 35">
              <path d="M12,12A6,6,0,1,0,6,6,6.006,6.006,0,0,0,12,12ZM12,2A4,4,0,1,1,8,6,4,4,0,0,1,12,2Z" />
              <path d="M12,14a9.01,9.01,0,0,0-9,9,1,1,0,0,0,2,0,7,7,0,0,1,14,0,1,1,0,0,0,2,0A9.01,9.01,0,0,0,12,14Z" />
            </svg>
          </label>
          <input type="text" name="gebruikersnaam" id="gebruikersnaam">
          <label for="wachtwoord" id="passwordSVG">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 35.021 35">
              <path
                d="m15 17a1 1 0 0 1 -1 1h-4a1 1 0 0 1 0-2h4a1 1 0 0 1 1 1zm-.293-9.707a1 1 0 0 0 -1.414 0l-1.293 1.293-1.293-1.293a1 1 0 1 0 -1.414 1.414l1.293 1.293-1.293 1.293a1 1 0 1 0 1.414 1.414l1.293-1.293 1.293 1.293a1 1 0 0 0 1.414-1.414l-1.293-1.293 1.293-1.293a1 1 0 0 0 0-1.414zm7.293 8.707h-4a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2zm-.586-6 1.293-1.293a1 1 0 1 0 -1.414-1.414l-1.293 1.293-1.293-1.293a1 1 0 1 0 -1.414 1.414l1.293 1.293-1.293 1.293a1 1 0 1 0 1.414 1.414l1.293-1.293 1.293 1.293a1 1 0 0 0 1.414-1.414zm-15.414 6h-4a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2zm.707-8.707a1 1 0 0 0 -1.414 0l-1.293 1.293-1.293-1.293a1 1 0 1 0 -1.414 1.414l1.293 1.293-1.293 1.293a1 1 0 1 0 1.414 1.414l1.293-1.293 1.293 1.293a1 1 0 1 0 1.414-1.414l-1.293-1.293 1.293-1.293a1 1 0 0 0 0-1.414z" />
            </svg>
          </label>
          <input type="password" name="wachtwoord" id="wachtwoord">
          <button type="submit" class="button" id="submit">Login</button>
        </div>
      </form>
    </div>
  </main>
</body>

</html>