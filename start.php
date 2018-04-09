<?php

    session_start();
    
    define('ROOT', dirname(__FILE__));

    include_once ROOT . '/functions/dbRepository.php';
    $repo = new DbRepository();
    
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $usertype = $repo->ValidateUser($email, $password);

    if($usertype == "user") {

        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        echo '<ul>';

        echo '</ul>';

    } else if($usertype == "admin") {

        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        echo '<ul>';
        echo '<li><a href="admin/add_user.php">Neuen Benutzer anlegen</a></li>';
        echo '<li><a href="admin/edit_user.php">Benutzer verwalten</a></li>';
        echo '<li><a href="admin/add_team.php">Neues Team anlegen</a></li>';
        echo '<li><a href="admin/add_event.php">Neues Event anlegen</a></li>';
        echo '</ul>';

    } else {

        echo '<p>Ungültiger Benutzername oder E-Mail Adresse.</p>';
        echo '<a href="index.html">Zurück</a>';

    }
?>