<?php

Interface IRepository {
    
    /* USER */
    public function AddUser($mail, $username, $password, $isAdmin);
    public function GetUser($mail, $password);
    public function DeleteUser($mail);

    /* TEAM */
    public function AddTeam($designation);
    public function DeleteTeam($designation);
    public function RenameTeam($oldDesignation, $newDesignation);

    /* Team Assignments */
    public function AddUserToTeam($userEmail, $teamDesignation);
    public function RemoveUserFromTeam ($userEmail, $teamDesignation);

    /* Event */
    public function AddEvent($designation, $description, $location, $startTime, $meetingTime, $meetingLocation, $umpsRequired, $scorerRequired, $seatsRequired);
    public function DeleteEvent($eventId);

    /* Event Assignments */
    public function AddEventToTeam($eventId, $teamId);
    public function RemoveEventFromTeam($eventId, $teamId);

    /* Event Participation */
    public function AddParticipation($userEmail, $eventId, $accepted, $note, $seats, $isUmpire, $isScorer, $isPlayer, $isCoach);
}

?>