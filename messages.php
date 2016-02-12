<?php

require_once('./src/connection.php');

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

echo '
    <form action="showUser.php">
        <input type="submit" value="Wróć do strony profilowej">
    </form>
';

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $userToShow = User::getUserById($userId);

    echo '<h3>Wiadomości odebrane</h3>';
    foreach (Message::loadAllReceivedMessages($userId) as $message) {
        $senderName = User::getUserById($message->getSenderId())->getName();
        $messageText = $message->getMessage();
        if (strlen($messageText) > 30) {
            $messageText = substr($messageText, 0, 30) . '...';
        }

        if($message->getStatus() == 1){
            echo 'WIADOMOSC JESZCZE NIE ZOSTAŁA PRZECZYTANA!';
        }

        echo '
            <div>Wiadomość od użytkownika: ' . $senderName . '</div>
            <div class="date"> Czas wysłania wiadomości: ' . $message->getDate() . '</div>
            <div class="message">' . $messageText . '</div><br>
        ';

        $message->setStatus(0);
        $message->saveToDb();
    }

    echo '<h3>Wiadomości wysłane</h3>';
    foreach (Message::loadAllSendMessages($userId) as $message) {
        $receiverName = User::getUserById($message->getReceiverId())->getName();
        $messageText = $message->getMessage();
        if (strlen($messageText) > 30) {
            $messageText = substr($messageText, 0, 30) . '...';
        }

        echo '
            <div>Wiadomość do użytkownika: ' . $receiverName . '</div>
            <div class="date"> Czas wysłania wiadomości: ' . $message->getDate() . '</div>
            <div class="message">' . $messageText . '</div><br>
        ';
    }
}
else {
    header('Location: login.php');
}