<?php

require_once('./src/connection.php');

if (!isset($_GET['userId']) && !isset($_SESSION['userId'])) {
    header('Location: login.php');
}

echo '
    <form action="showUser.php">
        <input type="submit" value="Wróć do strony profilowej">
    </form>
';

$singleTweet = User::loadSingleTweet($_GET['tweetId']);

echo 'Post został napisany przez użytkownika: ' . $_GET['userName'];

echo '<div class="date"> Czas tweeta: ' . $singleTweet['post_d'] . ' Id tweeta: ' . $singleTweet['id'] . '</div>';
echo '<div class="tweet">' . $singleTweet['tweet'] . '</div>';
echo '<div style=" margin: 60px 0px"></div>';

foreach (User::loadAllComments($singleTweet['id']) as $comment) {
    echo '<div class="date"> Czas komentarza: ' . $comment['comment_date'] . ' . </div>';
    echo '<div class="comment">' . $comment['comment_text'] . '</div><br>';
}

