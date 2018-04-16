<?php

    session_start();

    define('ROOT', $_SESSION["leps_root"]);    
    include ROOT . '/functions/session.inc.php';
    
    Logout();

?>