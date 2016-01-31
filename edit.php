<?php

require_once("./src/connection.php");

if (isset($_SESSION['userId'])) {
    echo "
<form action='edit.php' method='POST'>
    <input type='text' name='desc'>
    <input type='submit' value='Zmien opis'>
</form>

<form action='showUser.php'>
    <input type='submit' value='Wroc do poprzedniej strony'>
</form>
";



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $newDesc = $_POST['desc'];
        $userId = $_SESSION['userId'];
        $upDesc = User::updateDescription($userId, $newDesc);
        if ($upDesc === true) {
            echo "Opis zmieniony";
        }
        else {
            echo "Operacja nie udana";
        }
    }
}
else {
    echo "Żaden użtkownik nie jest zalogowany";
}
?>