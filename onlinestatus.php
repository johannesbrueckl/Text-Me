<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
} else {
    die(header('Location: login.php'));
}
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');
//instantiate DatabaseHelper class
$database = new DatabaseHelper();

$benutzer_id = $database->getBenutzerIDByName($_SESSION['username']);

//Hidden_value is the current online status.
if ($_POST['hidden_value'] == 'online') {
    $database->goOfflineByBenutzerID($benutzer_id);
} else {
    $database->goOnlineByBenutzerID($benutzer_id);
}
header('Location: mainpage.php');