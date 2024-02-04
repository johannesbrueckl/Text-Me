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

$database->deletePostByID($_GET['id']);

header('Location: mainpage.php');