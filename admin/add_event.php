<?php

if(isset($_POST["submit_new_event"])) {

    include '../functions/dbRepository.php';
    $repo = new DbRepository();

    // Save new event
    $designation = $_POST["event_new_designation"];
    $description = $_POST["event_new_description"];
    $location = $_POST["event_new_location"];
    $time = $_POST["event_new_time"];
    $meetingLocation = $_POST["event_new_meeting_location"];
    $meetingTime = $_POST["event_new_meeting_time"];

    $seats = $_POST["event_new_seats"];
    $umpires = $_POST["event_new_umpires"];
    $scorers = $_POST["event_new_scorers"];

    // TODO - Errorhandling on Inserting User
    $repo->AddEvent($designation, $description, $location, $time, $meetingLocation, $meetingTime, $seats, $umpires, $scorers);

    // header('Location: ../start.php');
} else {
    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>Neues Event anlegen</h2>';
    echo '<form action="add_event.php" method="POST">';
    
    echo 'Bezeichnung <input type="text" name="event_new_designation"/><br/>';
    echo 'Beschreibung <br/><textarea name="event_new_description" cols="40" rows="5"></textarea><br/>';
    echo 'Ort <input type="text" name="event_new_location"/><br/>';
    echo 'Startzeit <input type="datetime-local" name="event_new_time"/><br/>';
    echo 'Treffpunkt <input type="text" name="event_new_meeting_location"/><br/>';
    echo 'Treffpunkt Zeitpunkt <input type="datetime-local" name="event_new_meeting_time"/><br/>';

    echo 'Plätze benötigt <input type="number" name="event_new_seats"/><br/>';
    echo 'Umpires benötigt <input type="number" name="event_new_umpires"/><br/>';
    echo 'Scorer benötigt <input type="number" name="event_new_scorers"/><br/>';

    echo '<input type="submit" name="submit_new_event"/>';
    echo '</form>';
}

?>