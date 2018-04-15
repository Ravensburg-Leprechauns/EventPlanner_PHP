<?php

    session_start();
    
    include_once 'constants.inc.php';
    include_once ROOT . '/functions/dbRepository.php';

    $repo = new DbRepository();
    
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $usertype = $repo->ValidateUser($email, $password);

    if($usertype == "user") {

        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        PrintUserOptions();

    } else if($usertype == "admin") {

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
        echo '</ul>';
        
    }
?>