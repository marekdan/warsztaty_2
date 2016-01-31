<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="./css/stylesheet.css"/>
</head>

<?php

require_once("./src/connection.php");

echo "
<form action='showUser.php'>
    <input type='submit' value='Wroc do poprzedniej strony'>
</form>
";

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    $userToShow = User::getUserById($userId);

    echo "Wiadomości oderbrane";
    foreach ($userToShow->loadAllReceivedMessages($userId) as $message) {
        echo '<div class="date"> Czas wysłania wiadomości: ' . $message['message_date'] . '</div>';

        echo '<div class="message">' . $message['message'] . '</div><br>';
    }

    echo "Wiadomości wysłane";
    foreach ($userToShow->loadAllSendMessages($userId) as $message) {
        echo '<div class="date"> Czas wysłania wiadomości: ' . $message['message_date'] . '</div>';

        echo '<div class="message">' . $message['message'] . '</div><br>';
    }
}
else{
    echo "Żadaen użytkownik nie jest zalogowany";
}

