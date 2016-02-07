<?php

require_once('./src/connection.php');

if (!isset($_GET['userId']) && !isset($_SESSION['userId'])) {
    header('Location: login.php');
}

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    if ($userId != $_SESSION['userId']) {
        //wysyłanie wiadomości jako zalogowany temu wywołanemu przez geta jednocześnie nie pozwalajac na wysyałenie wiadomosci do samego siebie
        echo '
            <form action="showUser.php" method="POST">
                <input type="text" name="message">
                <input type="hidden" value="$userId" name="receiver">
                <input type="submit" value="Wyslij wiadomość">
            </form>

            <a href="showUser.php">
                <div class="button">POKAŻ SWOJ PROFIL</div>
            </a>
        ';
    }
}
else {
    $userId = $_SESSION['userId'];
}

$userToShow = User::getUserById($userId);
$currentlyLoggedUser = User::getUserById($_SESSION['userId']);

echo '<div id="loginfo"> Jesteś zalogowany jako:' . "{$currentlyLoggedUser->getName()}" . '</div><br>';
echo '<a href="showAllUsers.php"><div class="button">POKAŻ LISTĘ UŻYTKOWNIKÓW</div></a><br>';

if ($userToShow !== false) {
    echo "<h1> Tweety użytkownika: {$userToShow->getName()} </h1>";
    if ($userToShow->getId() === $_SESSION['userId']) {
        echo '
            <form action="showUser.php" method="POST">
                <input type="text" name="tweet_text">
                <input type="submit" value="Wyslij tweeta">
            </form>

            <form action="edit.php">
                <input type="submit" value="Edytuj swoj profil">
            </form>

            <form action="messages.php">
                <input type="submit" value="Sprawdź wiadomośći">
            </form>

            <form action="logout.php">
                <input type="submit" value="Wyloguj">
            </form>
            <br>
        ';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['tweet_text'] != null) {
                User::addTweet($userId, $_POST['tweet_text'], date('Y-m-d G:i:s'));
            }
            else {
                echo 'Twoj tweet jest pusty, jeżeli chcesz go wysłać to wprowadź do niego tekst';
            }

            $message = null;
            $message = $_POST['message'];

            if ($message != null) {
                User::sendMessage($currentlyLoggedUser->getId(), $_POST['receiver'], $message, date('Y-m-d G:i:s'));
                header('Location: showUser.php?userId=' . $_POST['receiver']);
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
