<?php

echo '<link rel="stylesheet" href="../CSS/base.css" type="text/css">';

if(isset($_POST["submit_news"])) {

    session_start();
    define('ROOT', $_SESSION["leps_root"]);
    
    include_once ROOT . '/functions/dbRepository.php';
    include_once ROOT . '/functions/session.inc.php';
    
    if(!ValidateCurrentUser())
        Logout();
        
    $repo = new DbRepository();

    // TODO - Errorhandling on Inserting User
    $repo->AddNews($_POST["news_designation"]);

    header('Location: ../start.php');
} else {
    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>News anlegen</h2>';
    echo '<form action="add_news.php" method="POST">';
    echo '<textarea cols=100 rows=20 name="news_designation"></textarea><br />';
    echo '<input type="submit" name="submit_news" value="Speichern"/>';
    echo '</form>';
}

?>