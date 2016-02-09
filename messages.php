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

        echo '
            <div>Wiadomość od użytkownika: ' . $senderName . '</div>
            <div class="date"> Czas wysłania wiadomości: ' . $message['message_date'] . '</div>
            <div class="message">' . $message['message'] . '</div><br>
        ';
    }

    echo '<h3>Wiadomości wysłane</h3>';
    foreach ($userToShow->loadAllSendMessages($userId) as $message) {

        $receiverName = User::getUserById($message['receiver_id'])->getName();

        echo '
            <div>Wiadomość do użytkownika: ' . $receiverName . '</div>
            <div class="date"> Czas wysłania wiadomości: ' . $message['message_date'] . '</div>
            <div class="message">' . $message['message'] . '</div><br>
        ';
    }
}
else {
    header('Location: login.php');
}