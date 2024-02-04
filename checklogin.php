<?php

session_start();
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();


//Fetching posted data.
$user = '';
if (isset($_POST['user'])) {
    $user = $_POST['user'];
}
$password = '';
if (isset($_POST['passw'])) {
    $password = $_POST['passw'];
}

//if values are whitespaces only, back to login page.
if (ctype_space($password) || ctype_space($user)) {
    die(header('Location: login.php'));
}

//Checks if login credentials are valid.
$success = $database->checkLoginData($user, $password);

//If credentials are valid session variables are changes for later tracking and redirected to mainpage.
// if not back to login site.
if ($success) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user;
    header('Location: mainpage.php');
} else {
    header('Location: login.php');
}