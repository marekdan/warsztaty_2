<?php

require_once('./src/connection.php');

if (!isset($_GET['userId']) && !isset($_SESSION['userId'])) {
    header('Location: login.php');
}

if (isset($_GET['userId'])) { //sprawdzenie czy użytkownik został wywołany przez geta czyli ze strony show all users
    $userId = $_GET['userId']; //ustawienie userid na tego wywołanego przez geta
    if ($userId != $_SESSION['userId']) { //jeżeli zalogowany uzytkownik sam nie wchodzi na swoj profil, to może komuś wysłać wiadomość
        echo '
            <form method="POST">
                <input type="hidden" name="forms" value="sending_message">
                <input type="hidden" name="receiver" value="' . $userId . '">
                <input type="text" name="message">
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

$userToShow = User::getUserById($userId); //użytkownik którego będzie pokazywany profil
$currentlyLoggedUser = User::getUserById($_SESSION['userId']); //aktualnie zalogowany użytkownik, może być ten sam co ma być pokazywany

echo '<div id="loginfo"> Jesteś zalogowany jako:' . $currentlyLoggedUser->getName() . '</div><br>';
echo '<div class="button"><a href="showAllUsers.php">POKAŻ LISTĘ UŻYTKOWNIKÓW</a></div><br>';

if ($userToShow !== false) {
    echo '<h1> Tweety użytkownika: ' . $userToShow->getName() . '</h1>';
    if ($userToShow->getId() === $_SESSION['userId']) {

        echo '
            <form action="showUser.php" method="POST">
                <input type="hidden" name="forms" value="adding_tweet">
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
    }
    foreach (Tweet::loadAllTweets($userToShow->getId()) as $tweet) {
        echo '<div class="date"> Czas tweeta: ' . $tweet['post_date'] . '</div>';
        echo '<div class="tweet">' . $tweet['tweet'] . '</div>';

        //formularz: dodanie komentarza do tweeta, każdy formularz pod tweetem zachowuje id danego tweeta dzięki inputowi hidden "tweet_id"
        echo '
            <form method="POST" >
                <input type="hidden" name="forms" value="adding_comment">
                <input type="text" name="comment">
                <input type="hidden" name="tweet_id" value="' . $tweet['id'] . '">
                <input type="submit" value="Dodaj komentarz">
            </form>
        ';

        $comment_counter = 0; //licznik komentarzy zawsze zaczyna od zera
        foreach (Comment::loadAllComments($tweet['id']) as $comment) {
            $comment_counter++; //zliczanie ilosci komentarzy
        }
        echo '<div class="comment">Ilość komentarzy: ' . $comment_counter . '<a href="show_post.php?tweetId=' . $tweet['id'] . '&userName=' . $userToShow->getName() . '"> POKAŻ WIĘCEJ</a></div>';
        echo '<div style=" margin: 60px 0px"></div>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['forms'] == 'sending_message') {
        if ($_POST['message'] != null) {
            Message::sendMessage($currentlyLoggedUser->getId(), $_POST['receiver'], $_POST['message'], date('Y-m-d G:i:s'));
            header('Location: showUser.php?userId=' . $_POST['receiver']);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['forms'] == 'adding_comment') {
        Comment::addComment($_POST['tweet_id'], $currentlyLoggedUser->getId(), $_POST['comment'], date('Y-m-d G:i:s'));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['forms'] == 'adding_tweet') {
        if ($_POST['tweet_text'] != null) {
            Tweet::create($currentlyLoggedUser->getId(), $_POST['tweet_text'], date('Y-m-d G:i:s'));
            header('Location: showUser.php');
        }
        else {
            echo 'Twoj tweet jest pusty, jeżeli chcesz go wysłać to wprowadź do niego tekst';
        }
    }
}