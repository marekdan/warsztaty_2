<?php

require_once ("./src/connection.php");

if(isset($_GET['userId'])){
    $userId = $_GET['userId'];
}
else{
    $userId = $_SESSION['userId'];
}

$userToShow = User::getUserById($userId);

if($userToShow !== false){
    echo "<h1> {$userToShow->getName()} </h1>";

    if($userToShow->getId() === $_SESSION['userId']){
        echo ("
        <form action='showUser.php' method='POST'>
            <input type='text' name='tweet_text'>
            <input type='submit'>
        </form>
        ");
    }

    foreach($userToShow->LoadAllTweets() as $tweet){
        echo 'Wyswietlć tweeta';
    }
}
else{
    echo 'Nie ma takiego usera...';
}