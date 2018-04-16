<?php

    function ValidateCurrentUser() {
        return isset($_SESSION["username"]) && isset($_SESSION["usertype"]);
    }

    function Logout() {
        $url = $_SESSION["leps_url"];

        session_unset();
        session_destroy();
        
        header('Location: ' . $url . '/index.html');
        exit();
    }

?>