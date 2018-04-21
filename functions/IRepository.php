<?php

Interface IRepository {
    
    public function GetLastInsertedId();

    /* USER */
    public function AddUser($mail, $username, $password, $isAdmin);
    public function GetUser($mail, $password);
    public function DeleteUser($mail);
    public function GetAllUsers();
    public function ValidateUser($mail, $password);

    /* TEAM */
    public function AddTeam($designation);
    public function GetAllTeams();
    public function DeleteTeam($designation);
    public function RenameTeam($oldDesignation, $newDesignation);

    /* Team Assignments */
    public function AddUserToTeam($userEmail, $teamDesignation);
    public function RemoveUserFromTeam ($userEmail, $teamDesignation);
    public function GetAllUsersInTeam($team);

    /* Event */
    public function AddEvent($designation, $description, $location, $startTime, $meetingTime, $meetingLocation, $umpsRequired, $scorerRequired, $seatsRequired);
    public function DeleteEvent($eventId);

    /* Event Assignments */
    public function AddEventToTeam($eventId, $team);
    public function RemoveEventFromTeam($eventId, $team);

    /* Event Participation */
    public function AddParticipation($userEmail, $eventId, $accepted, $note, $seats, $isUmpire, $isScorer, $isPlayer, $isCoach);

    /* Mail Configuration */
    public function GetMailConfiguration();

    /* News */
    public function AddNews($text);
    public function GetLatestNews();
}

?>