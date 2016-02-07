<?php

require_once('./src/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::RegisterUser($_POST['name'], $_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['description']);
    if ($user !== false) {
        $_SESSION['userId'] = $user->getId();
        header('Location: showUser.php');
    }
    else {
        echo 'Złe dane rejstracji';
    }
}

?>

<head>
    <meta charset="utf-8">
</head>
<form action="register.php" method="POST">
    <label>
        Email:
        <input type="email" name="email">
    </label>
    <br>
    <label>
        Name:
        <input type="text" name="name">
    </label>
    <br>
    <label>
        Hasło:
        <input type="password" name="password1">
    </label>
    <br>
    <label>
        Hasło 2:
        <input type="password" name="password2">
    </label>
    <br>
    <label>
        Opis:
        <input type="text" name="description">
    </label>
    <input type="submit" value="Przeslij">
</form>
<br>
<a href='login.php'>Wróc do strony logowania</a><br>