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


//Sends you back to your own Page if you searched for user that doesn't exist.
//TODO: feedback to user.
$benutzer_id = $database->getBenutzerIDByName($_GET['user']);
if (empty($benutzer_id)) {
    die(header('Location: mainpage.php'));
}
//benutzer_id here instead of wall_id becuase they are equal.
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
        'text' => $post_text_,
        'user' => $post_user,
        'date' => $post_date,
        'reaktions' => $reaktion_amount

    );
    array_push($post_collection, $temp_post_collection);
}

rsort($post_collection);
//Gets the Benutzer_ID of the Page owner (not current Benutzer!)
$other_user_id = $database->getBenutzerIDByName($_GET['user']);
?>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
<meta name="generator" content="Hugo 0.88.1">
<title><?php echo $_GET['user'] . "s Profile" ?></title>

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

<h1 id="wallname"><?php echo $_GET['user'] . "s Wall" ?></h1>

<!--Non clickable Button that displays the online status of owner of the Page.-->
<form id="online_status" action="">
    <button id="online_status_button_other" class="w-100 btn btn-lg btn-primary"
            type="submit"><?php echo $database->getOnlineStatusByBenutzerID($other_user_id) ?></button>
</form>

<!--Button that lets you return to your own Page.-->
<form id="return" action="mainpage.php">
    <div class="form-floating_return">
        <button id="send_button" class="w-100 btn btn-lg btn-primary" type="submit">return to own Wall</button>
    </div>
</form>

<!--Lets you search and visit the Pages of other user.-->
<form id="benutzer_search_other" action="otheruser.php" method="get">
    <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="look for your friends" name="user"
               required>
        <button id="send_button" class="w-100 btn btn-lg btn-primary" type="submit">search</button>
    </div>
</form>

<!--Form that lets you Post new posts to the other users Wall-->
<form id="post_input" action="post.php" method="post">
    <div class="form-floating_text">
        <input type="text" class="form-control" id="floatingInput" placeholder="spill the tea..."
               name="post_text"
               maxlength="230" autocomplete="off" required autofocus>
        <input type="hidden" id="userWallID" name="userWallID" value="<?php echo $benutzer_id ?>" >
        <input type="hidden" id="user" name="user" value="<?php echo $_GET['user'] ?>" >
        <button id="send_button" class="w-100 btn btn-lg btn-primary" type="submit">Say it loud!</button>
        <label <small> 230 characters maximum </small> </label>
    </div>
</form>

<!--Feed that Displays all Posts by the owner of the Page.-->
<div class="list-group-other">
    <a> <?php echo $database->postsByUser($benutzer_id) ?> total Posts. working real hard!</a>
    <?php foreach ($post_collection as $item): ?>
        <a href="reaktion.php?id=<?php echo $item['post_id']; ?>&user=<?php echo $_GET['user'] ?>"
           class="list-group-item list-group-item-action flex-column align-items-start active">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?php echo $item['user']; ?></h5>
                <small><?php echo $item['date']; ?></small>
            </div>
            <p class="mb-1"><?php echo $item['text']; ?></p>
            <small><?php echo $item['reaktions'] . " Like(s)"; ?>
                <br>
            </small>
        </a>
    <?php endforeach; ?>
</div>
<br>
</body>
</html>




