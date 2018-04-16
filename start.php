<?php

    session_start();

    $_SESSION["leps_root"] = dirname(__FILE__);
    $_SESSION["leps_url"] = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    
    define('ROOT', $_SESSION["leps_root"]);

    include_once ROOT . '/functions/dbRepository.php';
    include_once ROOT . '/functions/session.inc.php';

    $repo = new DbRepository();
    
    if(!ValidateCurrentUser() && !$repo->ValidateUser($_POST["email"], $_POST["password"])) {
        Logout();
    }

    if($_SESSION["usertype"] == "user") {

        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        PrintUserOptions();

    } else if($_SESSION["usertype"] == "admin") {

        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        echo '<h3>Administration</h3>';
        echo '<ul>';
        echo '<li><a href="admin/add_user.php">Neuen Benutzer anlegen</a></li>';
        echo '<li><a href="admin/edit_user.php">Benutzer verwalten</a></li>';
        echo '<li><a href="admin/add_team.php">Neues Team anlegen</a></li>';
        echo '<li><a href="admin/add_event.php">Neues Event anlegen</a></li>';
        echo '</ul>';

        PrintUserOptions();

    } else {

        echo '<p>Ungültiger Benutzername oder E-Mail Adresse.</p>';
        echo '<a href="index.html">Zurück</a>';

    }

    function PrintUserOptions() {

        echo '<h3>Navigation</h3>';
        echo '<ul>';
        echo '<li>Ausstehende Events</li>';
        echo '<li>Aktuelle Events</li>';
        echo '<li>Vergangene Events</li>';
        echo '<li>Einstellungen</li>';
        echo '<li><a href="logout.php">Abmelden</a></li>';
        echo '</ul>';
        
    }
?>