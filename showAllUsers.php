<?php

require_once('./src/connection.php');

if (!isset($_GET['userId']) && !isset($_SESSION['userId'])) {
    echo '
        Aby zobaczyć użytkowników należy się zalogować
        <br>
        <form action="login.php">
            <input type="submit" value="Zaloguj się">
        </form>
    ';
}
else {
    echo '
        <div class="button"><a href="showUser.php">POKAŻ SWOJ PROFIL</a></div>
    ';

    $allUsers = User::GetAllUsers();

    foreach ($allUsers as $userToShow) {
        echo '<div id="usershow" ><h2>' . $userToShow->getName() . '</h2>';
        echo '<a href="showUser.php?userId=' . $userToShow->getId() . '">Odwiedz profil</a> </div>';
    }
}