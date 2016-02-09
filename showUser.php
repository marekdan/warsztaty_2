<?php

require_once('./src/connection.php');

if (!isset($_GET['userId']) && !isset($_SESSION['userId'])) {
    header('Location: login.php');
}

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    if ($userId != $_SESSION['userId']) {
        //wysyłanie wiadomości jako zalogowany temu wywołanemu przez geta jednocześnie nie pozwalając na wysłanie wiadomości do samego siebie
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

$userToShow = User::getUserById($userId); //użytkownik którego będzie pokazywany profil
$currName = $userToShow->getName();
$currentlyLoggedUser = User::getUserById($_SESSION['userId']); //aktualnie zalogowany użytkownik, może być ten sam co ma być pokazywany

echo '<div id="loginfo"> Jesteś zalogowany jako:' . $currentlyLoggedUser->getName() . '</div><br>';
echo '<div class="button"><a href="showAllUsers.php">POKAŻ LISTĘ UŻYTKOWNIKÓW</a></div><br>';

if ($userToShow !== false) {
    echo "<h1> Tweety użytkownika: {$userToShow->getName()} </h1>";
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['forms'] == 'adding_tweet') {
            if ($_POST['tweet_text'] != null) {
                User::addTweet($userId, $_POST['tweet_text'], date('Y-m-d G:i:s'));
            }
            else {
                echo 'Twoj tweet jest pusty, jeżeli chcesz go wysłać to wprowadź do niego tekst';
            }

            if ($_POST['message'] != null) {
                User::sendMessage($currentlyLoggedUser->getId(), $_POST['receiver'], $message, date('Y-m-d G:i:s'));
                header('Location: showUser.php?userId=' . $_POST['receiver']);
            }
        }
    }

    foreach ($userToShow->loadAllTweets($userId) as $tweet) {
        echo '<div class="date"> Czas tweeta: ' . $tweet['post_d'] . '</div>';
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
        foreach ($userToShow->loadAllComments($tweet['id']) as $comment) {
            $comment_counter++; //zliczanie ilosci komentarzy
        }
//        echo '<div class="comment_counter">Ilość komentarzy: ' . $comment_counter . '<a href="show_post.php?tweetId=' . $tweet['id'] . '&userName=' . $userToShow['name'] . ' ">POKAŻ WIĘCEJ</a> </div>';
        echo '<div class="comment">Ilość komentarzy: ' . $comment_counter . '<a href="show_post.php?tweetId='.$tweet['id'].'&userName='. $userToShow->getName() .'"> POKAŻ WIĘCEJ</a></div>';
        //<a href="show_post.php?tweetId=' . $tweet['id'] . '&userName=' . $userToShow['name'] . ' ">POKAŻ WIĘCEJ</a> </div>';
        echo '<div style=" margin: 60px 0px"></div>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['forms'] == 'adding_comment') {
        User::addComment($_POST['tweet_id'], $currentlyLoggedUser->getId(), $_POST['comment'], date('Y-m-d G:i:s'));
    }
}
else {
    echo 'Nie ma takiego usera...';
}
