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

//Creates a new TextPost with Post_ID of 'total posts in network +1' and connects it with Wall_ID and Benutzer_ID via table hat.
$database->addNewPost($_POST['post_text'], $_SESSION['username']);
//Get Benutzer_ID of current posting user.
$benutzer_id = $database->getBenutzerIDByName($_SESSION['username']);
//Gets number of current amount of total posts. Thats the TextPost_ID of the created TextPost.
$post_amount = $database->getNumberTotalPosts();

//$registerd_user = $database->getBenutzerNamen();
//Sets wall_id to benutzer_id because they are equal. for readability purpose.
$wall_id = $_POST['userWallID'];
//connects post with posting user and recieving users wall.
$database->addRelationToHat($post_amount, $benutzer_id, $wall_id);
if ($wall_id == $benutzer_id) {
    header('Location: mainpage.php');
}
else {
    header('Location: otheruser.php?user=' . $_POST['user']);
}