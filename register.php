<?php
session_start();
// Include DatabaseHelper.php file
require_once('DatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new DatabaseHelper();
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Text Me login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">


    <!-- Custom styles for this template -->
    <link href="login.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
</head>
<body class="text-center">

<!--Form to insert the new Benutzer credentials and submit them to register.-->
<main class="form-signin">
    <form action="registerinsert.php" method="post">
        <h1 class="h3 mb-3 fw-normal">Sign up</h1>
        <br>
        <div class="form-floating">
            <label for="floatingInput">Username</label>
            <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="user" required
                   autofocus>
        </div>
        <br>
        <div class="form-floating">
            <label for="floatingPassword">Password</label>
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="passw"
                   required>
        </div>
        <br>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
        <p class="mt-5 mb-3 text-muted">established 2022</p>
    </form>
</main>


</body>
</html>
