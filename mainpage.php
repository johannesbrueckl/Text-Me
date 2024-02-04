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

//Gets user name from session.
$benutzer_id = $database->getBenutzerIDByName($_SESSION['username']);
//benutzer_id instead of wall_id here because they are equal.
$post_id_array = $database->getPostsByWallID($benutzer_id);

//Creates an array of arrays that store all data to all posts by user of this session and sorts it.
$post_collection = array();
foreach ($post_id_array as $value) {
    $post_text_ = $database->getPostTextByID($value);
    $post_date = $database->getPostDateByID($value);
    $post_user = $database->getUsernameByID($value);
    $reaktion_amount = $database->getNumberReaktionsByPostID($value);
    $temp_post_collection = array(
        'post_id' => $value,
        'date' => $post_date,
        'text' => $post_text_,
        'user' => $post_user,
        'reaktions' => $reaktion_amount
    );
    array_push($post_collection, $temp_post_collection);
}
rsort($post_collection);
?>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
<meta name="generator" content="Hugo 0.88.1">
<title><?php echo $_SESSION['username'] . "s Profile" ?></title>

<link href="mainpage.css" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<head>
    <title>Main Page</title>
</head>
<body>
<a id="logout" href="logout.php">Logout</a>

<h1 id="wallname"><?php echo $_SESSION['username'] . "s Wall" ?></h1>

<!--Displays and lets you change the online status.-->
<form id="online_status" action="onlinestatus.php" method="post">
    <input type="hidden" id="hidden_value" name="hidden_value"
           value="<?php echo $database->getOnlineStatusByBenutzerID($benutzer_id) ?>">
    <button id="online_status_button" class="w-100 btn btn-lg btn-primary"
            type="submit"><?php echo $database->getOnlineStatusByBenutzerID($benutzer_id) ?></button>
</form>

<!--Lets you search and visit the Pages of other user.-->
<form id="benutzer_search" action="otheruser.php" method="get">
    <div class="form-floating_seach">
        <input type="text" class="form-control" id="floatingInput" placeholder="look for your friends" name="user"
               required>
        <button id="send_button" class="w-100 btn btn-lg btn-primary" type="submit">search</button>
    </div>
</form>

<!--Form that lets you Post new posts to your own page.-->
<form id="post_input" action="post.php" method="post">
    <div class="form-floating_text">
        <input type="text" class="form-control" id="floatingInput" placeholder="come on, say something..."
               name="post_text"
               maxlength="230" autocomplete="off" required autofocus>
        <input type="hidden" id="userWallID" name="userWallID" value="<?php echo $benutzer_id ?>" >
        <button id="send_button" class="w-100 btn btn-lg btn-primary" type="submit">Say it loud!</button>
        <label <small> 230 characters maximum </small> </label>
    </div>
</form>

<!--Feed that Displays all Posts by the owner of the Page.-->
<div class="list-group">
    <a> <?php echo $database->postsByUser($benutzer_id) ?> total Posts. keep posting!</a>
    <?php foreach ($post_collection as $item): ?>
        <a href="reaktion.php?id=<?php echo $item['post_id']; ?>&user=<?php echo $_SESSION['username'] ?>"
           class="list-group-item list-group-item-action flex-column align-items-start active">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?php echo $item['user']; ?></h5>
                <small><?php echo $item['date']; ?></small>
            </div>
            <p class="mb-1"><?php echo $item['text']; ?></p>
            <small><?php echo $item['reaktions'] . " Like(s)"; ?>
                <a id="delete" href="deletepost.php?id=<?php echo $item['post_id']; ?>">delete</a>
            </small>
        </a>
    <?php endforeach; ?>
</div>
</body>
</html>




