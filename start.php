<?php

    session_start();

    $_SESSION["leps_root"] = dirname(__FILE__);
    $_SESSION["leps_url"] = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    
    define('ROOT', $_SESSION["leps_root"]);

    echo '<link rel="stylesheet" href="CSS/base.css" type="text/css">';

    include_once ROOT . '/functions/dbRepository.php';
    include_once ROOT . '/functions/session.inc.php';

    $repo = new DbRepository();
    
    if(!ValidateCurrentUser() && !$repo->ValidateUser($_POST["email"], $_POST["password"])) {
        Logout();
    }

    if($_SESSION["usertype"] == "user") {

        echo '<div id="main_menu">';
        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        PrintUserOptions();
        PrintLatestNews($repo);

    } else if($_SESSION["usertype"] == "admin") {

        echo '<div id="main_menu">';
        echo '<h2>Hallo ' . $_SESSION["username"] . '</h2>';
        echo '<h3>Administration</h3>';
        echo '<ul>';
        echo '<li><a href="admin/add_user.php">Neuen Benutzer anlegen</a></li>';
        echo '<li><a href="admin/edit_user.php">Benutzer verwalten</a></li>';
        echo '<li><a href="admin/add_team.php">Neues Team anlegen</a></li>';
        echo '<li><a href="admin/add_event.php">Neues Event anlegen</a></li>';
        echo '<li><a href="admin/current_events.php">Aktuelle Events einsehen</a></li>';
        echo '<li><a href="admin/add_news.php">News</a></li>';
        echo '</ul>';

        PrintUserOptions();
        PrintLatestNews($repo);

    } else {

        echo '<p>Ungültiger Benutzername oder E-Mail Adresse.</p>';
        echo '<a href="index.html">Zurück</a>';

    }

    function PrintUserOptions() {

        echo '<h3>Navigation</h3>';
        echo '<ul>';
        echo '<li>Ausstehende Events</li>';
        echo '<li><a href="user/new_events.php">Aktuelle Events</a></li>';
        echo '<li>Vergangene Events</li>';
        echo '<li>Einstellungen</li>';
        echo '<li><a href="logout.php">Abmelden</a></li>';
        echo '</ul>';
        echo '</div>';
        
    }

    function PrintLatestNews($repo) {
        echo '<div id="news">';

        echo '<h3>Neuigkeiten</h3>';
        echo '<p class="preserveLineBreaks">';

        echo $repo->GetLatestNews();

        echo '</p>';

        echo '</div>';
    }
?>