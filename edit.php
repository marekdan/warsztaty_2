<?php

require_once('./src/connection.php');

if (isset($_SESSION['userId'])) {
    echo '
        <form action="edit.php" method="POST">
            <input type="hidden" name="actions" value="desc">
            <input type="text" name="desc">
            <input type="submit" value="Zmień opis">
        </form>

        <form action="edit.php" method="POST">
            <input type="hidden" name="actions" value="password_change">
            <input type="text" name="new_pass">
            <input type="submit" value="Zmień hasło">
        </form>

        <form action="showUser.php">
            <input type="submit" value="Wróć do poprzedniej strony">
        </form>
    ';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['actions'] == 'desc') {
        $newDesc = $_POST['desc'];
        $userId = $_SESSION['userId'];
        $upDesc = User::updateDescription($userId, $newDesc);
        if ($upDesc === true) {
            echo 'Opis zmieniony';
        }
        else {
            echo 'Operacja nie udana';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['actions'] == 'password_change') {
        $newPass = $_POST['new_pass'];
        $userId = $_SESSION['userId'];
        $upPass = User::updatePassword($userId, $newPass);
        if ($upPass === true) {
            echo 'Hasło zmienione';
        }
        else {
            echo 'Operacja nie udana';
        }
    }
}
else {
    header('Location: login.php');
}