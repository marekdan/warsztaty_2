<?php

require_once('./src/connection.php');

unset ($_SESSION['userId']);
header('Location: login.php');