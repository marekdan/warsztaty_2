<?php

require_once('./src/connection.php');

echo '
    <form action="showUser.php">
        <input type="submit" value="Wróć do poprzedniej strony">
    </form>
';

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $userToShow = User::getUserById($userId);

    echo '<h3>Wiadomości odebrane</h3>';
    foreach ($userToShow->loadAllReceivedMessages($userId) as $message) {

        $senderName = User::getUserById($message['sender_id'])->getName();

        $messageText = $message['message'];
        if (strlen($messageText) > 30) {
            $messageText = substr($messageText, 0, 30) . '...';
        }

        echo '
            <div>Wiadomość od użytkownika: ' . $senderName . '</div>
            <div class="date"> Czas wysłania wiadomości: ' . $message['message_date'] . '</div>
            <div class="message">' . $messageText . '</div><br>
        ';
    }

    echo '<h3>Wiadomości wysłane</h3>';
    foreach ($userToShow->loadAllSendMessages($userId) as $message) {

        $receiverName = User::getUserById($message['receiver_id'])->getName();

        $messageText = $message['message'];
        if (strlen($message['message']) > 30) {
            $messageText = substr($messageText, 0, 30) . '...';
        }

        echo '
            <div>Wiadomość do użytkownika: ' . $receiverName . '</div>
            <div class="date"> Czas wysłania wiadomości: ' . $message['message_date'] . '</div>
            <div class="message">' . $messageText . '</div><br>
        ';
    }
}
else {
    header('Location: login.php');
}