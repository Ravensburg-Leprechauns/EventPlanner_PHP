<?php

session_start();

define('ROOT', $_SESSION["leps_root"]);

include_once ROOT . '/classes/Event.php';
include_once ROOT . '/classes/EventParticipation.php';
include_once ROOT . '/functions/dbRepository.php';
include_once ROOT . '/functions/mailer.inc.php';
include_once ROOT . '/functions/session.inc.php';

echo '<link rel="stylesheet" href="../CSS/base.css" type="text/css">';

if(!ValidateCurrentUser())
    Logout();

echo '<a href="../start.php">Zurück</a>';
echo '<h2>Zu- &amp; Absagen einsehen</h2>';

$repo = new DbRepository();
$unfinishedEvents = $repo->GetAllUnfinishedEvents();

foreach($unfinishedEvents as $event) {
    $eventParticipations = $repo->GetEventParticipations($event);

   

    if(sizeof($eventParticipations) > 0) {
        
        echo "<h3>$event->Designation</h3>";
    
    echo "<table>
    <thead>
      <tr>
        <td>Benutzer</td>
        <td>Zugesagt</td>
        <td>Spieler</td>
        <td>Coach</td>
        <td>Umpire</td>
        <td>Scorer</td>
        <td>Plätze</td>
        <td>Bemerkung</td>
      </tr>
    </thead>
    <tbody>";

    foreach($eventParticipations as $participation) {

        if($participation->Accepted == 0) {
            $participation->Accepted = "Nein";
        } else {
            $participation->Accepted = "Ja";
        }

        if($participation->IsPlayer == 0) {
            $participation->IsPlayer = "Nein";
        } else {
            $participation->IsPlayer = "Ja";
        }

        if($participation->IsCoach == 0) {
            $participation->IsCoach = "Nein";
        } else {
            $participation->IsCoach = "Ja";
        }

        if($participation->IsUmpire == 0) {
            $participation->IsUmpire = "Nein";
        } else {
            $participation->IsUmpire = "Ja";
        }

        if($participation->IsScorer == 0) {
            $participation->IsScorer = "Nein";
        } else {
            $participation->IsScorer = "Ja";
        }

        echo "<tr>
            <td>$participation->Username</td>
            <td>$participation->Accepted</td>
            <td>$participation->IsPlayer</td>
            <td>$participation->IsCoach</td>
            <td>$participation->IsUmpire</td>
            <td>$participation->IsScorer</td>
            <td>$participation->Seats</td>
            <td>$participation->Note</td>
        </tr>";
    }

    echo "</tbody></table>";
    }
}

?>