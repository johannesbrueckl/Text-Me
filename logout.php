<?php
//Ends the session and returns to login page.
session_start();
session_destroy();
header('Location: login.php');
exit;