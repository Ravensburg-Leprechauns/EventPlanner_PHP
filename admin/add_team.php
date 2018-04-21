<?php

echo '<link rel="stylesheet" href="../CSS/base.css" type="text/css">';

if(isset($_POST["team_new_designation"])) {

    session_start();
    define('ROOT', $_SESSION["leps_root"]);
    
    include_once ROOT . '/functions/dbRepository.php';
    include_once ROOT . '/functions/session.inc.php';
    
    if(!ValidateCurrentUser())
        Logout();
        
    $repo = new DbRepository();

    // TODO - Errorhandling on Inserting User
    $repo->AddTeam($_POST["team_new_designation"]);

    header('Location: ../start.php');
} else {
    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>Neues Team anlegen</h2>';
    echo '<form action="add_team.php" method="POST">';
    echo '<label>Bezeichnung: <input type="text" name="team_new_designation" /></label><br />';
    echo '<input type="submit" value="Speichern"/>';
    echo '</form>';
}

?>