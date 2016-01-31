<?php

require_once ("./src/connection.php");



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = User::logInUser($_POST['email'], $_POST['password']);
    //$user = User::logInUser('test@pl', '12345');
            if($user !== false){
                $_SESSION['userId'] = $user->getId();
                header('Location: showUser.php');
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
<!--<a href='register.php'>Strona z Rejstracja</a><br>-->