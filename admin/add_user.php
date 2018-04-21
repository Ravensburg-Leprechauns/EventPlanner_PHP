<?php

session_start();

define('ROOT', $_SESSION["leps_root"]);

include_once ROOT . '/functions/dbRepository.php';
include_once ROOT . '/functions/session.inc.php';

echo '<link rel="stylesheet" href="../CSS/base.css" type="text/css">';

if(!ValidateCurrentUser())
    Logout();

if(isset($_POST["user_new_username"]) && isset($_POST["user_new_email"]) && isset($_POST["user_new_password"]) && isset($_POST["user_new_password_confirmation"])) {

    if($_POST["user_new_password"] == $_POST["user_new_password_confirmation"]) {
        
        $repo = new DbRepository();

        // TODO - Errorhandling on Inserting User
        $repo->AddUser($_POST["user_new_email"], $_POST["user_new_username"], $_POST["user_new_password"], false);

        header('Location: ../start.php');
    } else {
        echo 'Die eingegebenen Kennwörter stimmen nicht überein!<br/><br/>';
        PrintLoginForm($_POST["user_new_username"], $_POST["user_new_email"]);
    }

} else {
    PrintLoginForm("", "");
}

function PrintLoginForm($username, $email) {
    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>Neuen Benutzer anlegen</h2>';
    echo '<form action="add_user.php" method="POST">';
    echo 'Benutzername: <input type="text" name="user_new_username" value="' . $username . '" /><br />';
    echo 'E-Mail Adresse: <input type="text" name="user_new_email" value="' . $email . '" /><br />';
    echo 'Kennwort: <input name="user_new_password" type="password" /><br />';
    echo 'Kennwort bestätigen: <input name="user_new_password_confirmation" type="password" /><br/>';
    echo '<input type="submit"/>';
    echo '</form>';
}
?>