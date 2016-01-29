<?php

require_once ("./src/connection.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = User::logInUser($_POST['email'], $_POST['password']);
            if($user !== false){
                //session_start();
                $_SESSION['userId'] = $user->getId();
                header('Location: showUser.php');
            }
}

?>

<form action="login.php" method="POST">
    <label>
        Email:
        <input type="email" name="mail">
    </label>
    <br>
    <label>
        Password:
        <input type="password" name="password">
    </label>
    <input type="submit">
</form>
