<?php

require_once ("./src/connection.php");

unset ($_SESSSION['userId']);
header('Location: login.php');