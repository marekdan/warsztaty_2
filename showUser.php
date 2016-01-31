<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="css/stylesheet.css"/>
</head>
<?php

require_once ("./src/connection.php");

if(isset($_GET['userId'])){
    $userId = $_GET['userId'];
    echo "
        <form action='showUser.php' method='POST'>
            <input type='text' name='message'>
            <input type='submit' value='Wyslij wiadomosc'> <!--wysylanie wiadomosci jako zalogowany temu wywolanemu przez geta-->
        </form>
        ";
}
else{
    $userId = $_SESSION['userId'];
}

$userToShow = User::getUserById($userId);

$currentlyLoggedUser = User::getUserById($_SESSION['userId']);
echo "<div id='loginfo'> Jestes zalogowany na: {$currentlyLoggedUser->getName()} </div>";

if($userToShow !== false){
    echo "<h1> Tweety uzytkownika: {$userToShow->getName()} </h1>";

    if($userToShow->getId() === $_SESSION['userId']){
        echo ("
        <form action='showUser.php' method='POST'>
            <input type='text' name='tweet_text'>
            <input type='submit' value='Wyslij tweeta'>
        </form>

        <form action='edit.php' method='OPTIONS'>
            <input type='submit' value='Edytuj swoj profil'>
        </form>

        <form action='logout.php' method='POST'>
            <input type='submit' value='Wyloguj'>
        </form>
        <br>
        ");

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            User::addTweet($userId, $_POST['tweet_text'], date('Y-m-d G:i:s'));
        }
    }

    //foreach($userToShow->LoadAllTweets($_SESSION['userId']) as $tweet){
    foreach($userToShow->LoadAllTweets($userId) as $tweet){
        echo '<div class="date"> Czas tweeta: ';
        echo $tweet['post_d'] . '</div>';

        echo '<div class="tweet">';
        echo $tweet['tweet'] . '</div><br>';


    }
}
else{
    echo 'Nie ma takiego usera...';
}
