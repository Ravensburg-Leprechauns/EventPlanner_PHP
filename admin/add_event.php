<?php

include ROOT . '/functions/dbRepository.php';
$repo = new DbRepository();
$teams = $repo->GetAllTeams();

if(isset($_POST["submit_new_event"])) {

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

    // Get new events id
    $eventId = $repo->GetLastInsertedId();

    foreach($teams as $team) {
        if(isset($_POST[$team])) {
            $repo->AddEventToTeam($eventId, $team);
        }
    }

    header('Location: ../start.php');
} else {
    echo '<a href="../start.php">Zurück</a>';
    echo '<h2>Neues Event anlegen</h2>';
    echo '<form action="add_event.php" method="POST">';
    
    echo '<label>Bezeichnung <input type="text" name="event_new_designation"/></label><br/>';
    echo '<label>Beschreibung <br/><textarea name="event_new_description" cols="40" rows="5"></textarea></label><br/>';
    echo '<label>Ort <input type="text" name="event_new_location"/></label><br/>';
    echo '<label>Startzeit <input type="datetime-local" name="event_new_time"/></label><br/>';
    echo '<label>Treffpunkt <input type="text" name="event_new_meeting_location"/></label><br/>';
    echo '<label>Treffpunkt Zeitpunkt <input type="datetime-local" name="event_new_meeting_time"/></label><br/>';

    echo '<label>Plätze benötigt <input type="number" name="event_new_seats"/></label><br/>';
    echo '<label>Umpires benötigt <input type="number" name="event_new_umpires"/></label><br/>';
    echo '<label>Scorer benötigt <input type="number" name="event_new_scorers"/></label><br/>';

    // TODO: Overflow CSS

    echo '<p>Freigabe für Teams:</p>';
    echo '<div>';

    foreach($teams as $team) {
        echo '<label><input type="checkbox" name="' . $team . '" />' . $team . '</label>';
    }

    echo '</div>';

    echo '<input type="submit" name="submit_new_event"/>';
    echo '</form>';
}

?>