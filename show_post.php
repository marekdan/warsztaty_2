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

$singleTweet = Tweet::loadSingleTweet($_GET['tweetId']);

echo 'Post został napisany przez użytkownika: ' . $_GET['userName'];

echo '<div class="date"> Czas tweeta: ' . $singleTweet->getDate() . ' Id tweeta: ' . $singleTweet->getId() . '</div>';
echo '<div class="tweet">' . $singleTweet->getText() . '</div>';
echo '<div style=" margin: 60px 0px"></div>';

foreach (Comment::loadAllComments($singleTweet->getId()) as $comment) {
    echo '<div class="date"> Czas komentarza: ' . $comment->getCommentDate() . '</div>';
    echo '<div class="comment">' . $comment->getText() . '</div><br>';
}