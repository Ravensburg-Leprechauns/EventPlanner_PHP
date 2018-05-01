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

$events = $repo->GetAllEvents($_SESSION['team']);

if (!empty($events)) {
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

        // TODO: Zu- / Absagen informationen

        echo "</div>";
    }
} else {
    echo "<p>Keine Events vorhanden</p>";
}

?>