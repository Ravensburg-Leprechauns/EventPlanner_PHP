<?php

if(isset($_POST["team_new_designation"])) {

       
    include ROOT . '/functions/dbRepository.php';
        
    $repo = new DbRepository();

    // TODO - Errorhandling on Inserting User
    $repo->AddTeam($_POST["team_new_designation"]);

    header('Location: ../start.php');
} else {
    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>Neues Team anlegen</h2>';
    echo '<form action="add_team.php" method="POST">';
    echo 'Bezeichnung: <input type="text" name="team_new_designation" /><br />';
    echo '<input type="submit"/>';
    echo '</form>';
}

?>