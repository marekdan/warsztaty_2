<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="css/stylesheet.css"/>
</head>
<a href='showUser.php'>
    <div class="button">POKAŻ SWOJ PROFIL</div>
</a>
<br>

<?php

require_once("./src/connection.php");

$allUsers = User::GetAllUsers();

foreach ($allUsers as $userToShow) {
    echo '<div id="usershow" ><h2>' . $userToShow->getName() . '</h2>';
    echo "<a href='showUser.php?userId={$userToShow->getId()}'>Odwiedz profil</a> </div>";
}

?>


