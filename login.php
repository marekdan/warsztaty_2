<?php

require_once('./src/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::logInUser($_POST['email'], $_POST['password']);
    if ($user !== false) {
        $_SESSION['userId'] = $user->getId();
        header('Location: showUser.php');
    }
    else{
        echo 'Błędne dane logowania';
    }
}

?>

<form action="login.php" method="POST">
    <label>
        Email:
        <input type="email" name="email">
    </label>
    <br>
    <label>
        Password:
        <input type="password" name="password">
    </label>
    <input type="submit">
</form>
<br>
<a href="register.php">Zarejestruj sie</a><br>