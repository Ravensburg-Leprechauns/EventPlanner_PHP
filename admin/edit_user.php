<?php

    include_once '../functions/dbRepository.php';
    include_once '../classes/User.php';

    $repo = new DbRepository();

    if(sizeof($_POST) > 0) {
        foreach($_POST as $uid => $team) {
            if($team != '') {
                $repo->AddUserToTeam($uid, $team);
            }
        }
        header('Location: ../start.php');
    }

    $users = $repo->GetAllUsers();
    $teams = $repo->GetAllTeams();

    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>Benutzer verwalten</h2>';
    echo '<form action="edit_user.php" method="POST">';
    echo '<table>';

    echo '<thead>';

        echo '<tr>';

            echo '<td>Benutzername</td>';
            echo '<td>E-Mail</td>';
            echo '<td>Team</td>';
            echo '<td></td>';

        echo '</tr>';

    echo '</thead>';

    foreach($users as $user) {

        echo '<tr>';

            echo '<td>' . $user->Username . '</td>';
            echo '<td>' . $user->Mail . '</td>';
            echo '<td><select name="' . $user->Id . '">';

            if($user->TeamDesignation == "" || $user->TeamDesignation == null) {
                echo '<option selected></option>';
            }

            foreach($teams as $team) {
                if($team == $user->TeamDesignation) {
                    echo '<option selected>' . $team . '</option>';
                } else {
                    echo '<option>' . $team . '</option>';
                }
            }

            echo '</select></td>';

        echo '</tr>';
    }

    echo '</table>';
    echo '<input type="submit" value="Speichern">';
    echo '</form>';

?>