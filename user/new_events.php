<?php

session_start();

define('ROOT', $_SESSION["leps_root"]);

include_once ROOT . '/classes/Event.php';
include_once ROOT . '/classes/User.php';
include_once ROOT . '/functions/dbRepository.php';
include_once ROOT . '/functions/session.inc.php';

echo '<link rel="stylesheet" href="../CSS/base.css" type="text/css">';

if(!ValidateCurrentUser())
    Logout();

$repo = new DbRepository();

echo '<a href="../start.php">Zurück</a>';
echo '<h2>Aktuelle Events</h2>';

$events = $repo->GetAllNewEvents($_SESSION["userid"], $_SESSION['team']);

if (!empty($events)) {
    echo "<form action='new_events.php' method='POST'>";

    foreach($events as $event) {

        echo "<div class='event'>";
        echo "<h3>$event->Designation</h3>";
        echo "<p>$event->Description</p>";
        echo "<p>Start: $event->Time / $event->Location</p>";
        echo "<p>Treffpunkt: $event->MeetingTime / $event->MeetingLocation</p>";
        if($event->SeatsRequired > 0) {
            echo "<p>$event->SeatsRequired Plätze benötigt</p>";
        }
        if($event->UmpiresRequired > 0) {
            echo "<p>$event->UmpiresRequired Umpires benötigt</p>";
        }
        if($event->ScorerRequired > 0) {
            echo "<p>$event->ScorerRequired Scorer benötigt</p>";
        }

        echo "<h4>Teilnehmen / Absagen</h4>";

        echo "<p>Ich ";
        echo "<select name='cbParticipate_$event->Id'>";
        echo "<option selected>bin noch unentschlossen</option>";
        echo "<option>bin dabei</option>";
        echo "<option>kann nicht teilnehmen</option>";
        echo "</select></p>";

        if($event->UmpiresRequired > 0) {
            echo "<p>
                    <label for='chkUmpire_$event->Id'>Ich stehe als Umpire zur Verfügung</label>
                    <input type='checkbox' id='chkUmpire_$event->Id' name='chkUmpire_$event->Id'></p>";
        }

        if($event->ScorerRequired > 0) {
            echo "<p>
                    <label for='chkScorer_$event->Id'>Ich stehe als Scorer zur Verfügung</label>
                    <input type='checkbox' id='chkScorer_$event->Id' name='chkScorer_$event->Id'></p>";
        }

        if($event->SeatsRequired > 0) {
            echo "<p>
                    <label for='chkSeats_$event->Id'>Ich kann fahren</label>
                    <input type='checkbox' id='chkSeats_$event->Id' name='chkSeats_$event->Id'>";
            echo "<label for='txtSeats_$event->Id'>Freie Plätze:</label>
                    <input type='numeric' id='txtSeats_$event->Id' name='txtSeats_$event->Id'></p>";
        }

        echo "</div>";
    }

    echo "</form>";
} else {
    echo "<p>Keine Events vorhanden</p>";
}

?>