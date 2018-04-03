<?php

    include 'dbaccess.inc.php';
    include 'IRepository.php';

    class DbRepository implements IRepository {
    
        private $dbConnection;

        function __construct() {
            $this->dbConnection = getDBConnection();
        }

        /* USER */
        public function AddUser($mail, $username, $password, $isAdmin) {

            $mail = $this->dbConnection->real_escape_string($mail);
            $username = $this->dbConnection->real_escape_string($username);
            $password = $this->dbConnection->real_escape_string($password);
            
            if($isAdmin) {
                $isAdmin = 1;
            } else {
                $isAdmin = 0;
            }

            $options = [
                'cost' => 12,
            ];
            $password = password_hash($password, PASSWORD_BCRYPT, $options);

            $query = "INSERT INTO user(mail, username, password, is_admin) VALUES ('$mail', '$username', '$password', $isAdmin)";
            $this->dbConnection->query($query);
        }

        public function GetUser($mail, $password) {

        }

        public function DeleteUser($mail) {

        }


        /* TEAM */
        public function AddTeam($designation) {

        }

        public function DeleteTeam($designation) {

        }
        
        public function RenameTeam($oldDesignation, $newDesignation) {
            
        }
                

        /* Team Assignments */
        public function AddUserToTeam($userEmail, $teamDesignation) {
            
        }
                
        public function RemoveUserFromTeam ($userEmail, $teamDesignation) {
            
        }   


        /* Event */
        public function AddEvent($designation, $description, $location, $startTime, $meetingTime, $meetingLocation, $umpsRequired, $scorerRequired, $seatsRequired) {
            
        }
                
        public function DeleteEvent($eventId) {
            
        }     


        /* Event Assignments */
        public function AddEventToTeam($eventId, $teamId) {
            
        }
                
        public function RemoveEventFromTeam($eventId, $teamId) {
            
        }


        /* Event Participation */
        public function AddParticipation($userEmail, $eventId, $accepted, $note, $seats, $isUmpire, $isScorer, $isPlayer, $isCoach) {
            
        }
               
    }
?>