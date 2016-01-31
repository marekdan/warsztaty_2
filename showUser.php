<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="css/stylesheet.css"/>
</head>
<?php

require_once("./src/connection.php");

if (isset($_GET['userId'])) {

    $userId = $_GET['userId'];

    if ($userId != $_SESSION['userId']) {
        echo "
        <form action='showUser.php' method='POST'>
            <input type='text' name='message'>
            <input type='submit' value='Wyslij wiadomosc'>
        </form>
        <!--wysyłanie wiadomości jako zalogowany temu wywołanemu przez geta
         jednocześnie nie pozwalajac na wysyałenie wiadomosci do samego siebie-->
        ";
    }
}
else {
    $userId = $_SESSION['userId'];
}

$userToShow = User::getUserById($userId);

$currentlyLoggedUser = User::getUserById($_SESSION['userId']);
echo "<div id='loginfo'> Jesteś zalogowany jako: {$currentlyLoggedUser->getName()} </div><br>";

//echo "<div id='link'><a href='showAllUsers.php'>Pokaż liste użytkownikó</a></div><br>";
echo "<a href='showAllUsers.php'><div id='link'>POKAŻ LISTĘ UŻYTKOWNIKÓW</div></a><br>";

if ($userToShow !== false) {
    echo "<h1> Tweety użytkownika: {$userToShow->getName()} </h1>";

    if ($userToShow->getId() === $_SESSION['userId']) {
        echo("
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($_POST['tweet_text'] != null){
            User::addTweet($userId, $_POST['tweet_text'], date('Y-m-d G:i:s'));
            }
            else{
                echo "Twoj tweet jest pusty, jeżeli chcesz go wysłać to wprowadź do niego tekst";
            }
        }
    }

    foreach ($userToShow->LoadAllTweets($userId) as $tweet) {
        echo '<div class="date"> Czas tweeta: ' . $tweet['post_d'] . '</div>';

        echo '<div class="tweet">' . $tweet['tweet'] . '</div><br>';
    }
}
else {
    echo 'Nie ma takiego usera...';
}
