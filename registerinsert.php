<?php
session_start();

//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Fetches the data handover by POST. password and name.
$user = '';
if (isset($_POST['user'])) {
    $user = $_POST['user'];
}
$password = '';
if (isset($_POST['passw'])) {
    $password = $_POST['passw'];
}

//gets all Benutzer.Name in database in array namen.
$namen = $database->getBenutzerNamen();

//if user is already taken, back to register page.
foreach ($namen as $value) {
    if ($user == $value) {
        die(header('Location: register.php'));
    }
}

//hash password with BCrypt and insert user and hashed password to new Benutzer.
$password_hashed = password_hash($password, PASSWORD_BCRYPT);
$success = $database->registerNewBenutzer($user, $password_hashed);
if($success) {
    $database->registerNewWall($user);
}

if ($success) {
    header('Location: login.php');
} else {
    header('Location: register.php');
}