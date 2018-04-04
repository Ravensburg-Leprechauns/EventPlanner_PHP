<?php

    include_once 'dbaccess.inc.php';
    include_once 'IRepository.php';
    include_once '../classes/User.php';

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

        public function GetAllUsers() {
            $query = "SELECT u.mail as mail, u.username as username, u.password as password, t.designation as team_designation  from user u LEFT OUTER JOIN team_assignment a ON u.mail = a.user_mail LEFT OUTER JOIN team t ON a.team_id = t.id";

            $result = $this->dbConnection->query($query);
            $users = array();
            
            while ($rowUser = mysqli_fetch_assoc($result)) {
                $user = new User();
                $user->Username = $rowUser["username"];
                $user->Mail = $rowUser["mail"];
                $user->EncryptedPassword = $rowUser["password"];
                $user->TeamDesignation = $rowUser["team_designation"];
                $users[] = $user;
            }

            return $users;
        }

        public function GetUser($mail, $password) {

        }

        public function DeleteUser($mail) {

        }


        /* TEAM */
        public function AddTeam($designation) {
            $designation = $this->dbConnection->real_escape_string($designation);

            $query = "INSERT INTO team(designation) VALUES ('$designation')";
            $this->dbConnection->query($query);
        }

        public function GetAllTeams() {
            $query = "SELECT designation from team";
            
            $result = $this->dbConnection->query($query);
            $teams = array();
            
            while ($rowTeam = mysqli_fetch_assoc($result)) {
                $teams[] = $rowTeam["designation"];
            }

            return $teams;
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