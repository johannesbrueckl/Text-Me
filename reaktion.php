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

//Adds a Reaktion for a corresponding Post_ID.
$benutzer_id = $database->getBenutzerIDByName($_SESSION['username']);
//checks if user has already liked the post. if not, likes it.
$success = $database->getReaktionAmountByPostIDAndBenutzerID($_GET['id'], $benutzer_id);
if ($success) {
    $database->reactToPostByPostIDAndBenutzerID($_GET['id'], $benutzer_id);
}
if ($_SESSION['username'] == $_GET['user']) {
    header('Location: mainpage.php');
}
else {
    header('Location: otheruser.php?user=' . $_GET['user']);
}

