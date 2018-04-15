<?php

    include_once ROOT . '/functions/dbaccess.inc.php';
    include_once ROOT . '/functions/IRepository.php';
    include_once ROOT . '/classes/User.php';
    include_once ROOT . '/classes/MailConfiguration.php';

    class DbRepository implements IRepository {
    
        private $dbConnection;

        public function __construct() {
            $this->dbConnection = getDBConnection();
        }

        public function __destruct() {
            $this->dbConnection = null;
        }

        public function GetLastInsertedId() {
            return $this->dbConnection->insert_id;
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
            $query = "SELECT u.id, u.mail as mail, u.username as username, u.password as password, t.designation as team_designation  from user u LEFT OUTER JOIN team_assignment a ON u.id = a.user_id LEFT OUTER JOIN team t ON a.team_designation = t.designation";

            $result = $this->dbConnection->query($query);
            $users = array();
            
            if($result) {
                while ($rowUser = mysqli_fetch_assoc($result)) {
                    $user = new User();
                    $user->Id = $rowUser["id"];
                    $user->Username = $rowUser["username"];
                    $user->Mail = $rowUser["mail"];
                    $user->EncryptedPassword = $rowUser["password"];
                    $user->TeamDesignation = $rowUser["team_designation"];
                    $users[] = $user;
                }
            }

            return $users;
        }

        public function GetUser($mail, $password) {

        }

        public function DeleteUser($mail) {

        }

        public function ValidateUser($mail, $password) {
            
            $mail = $this->dbConnection->real_escape_string($mail);
            $query = "SELECT is_admin, username, password FROM user WHERE mail = '$mail'";
            $result = $this->dbConnection->query($query);
            $row = $result->fetch_assoc();
            
            if($result->num_rows === 0 || !password_verify($password , $row["password"])) {
                return null;
            } else {

                $id = $row['is_admin'];
                $username = $row['username'];
    
                if($id == 0) {
                    $_SESSION["username"] = $username;
                    return "user";
                } else if($id == 1) {
                    $_SESSION["username"] = $username;
                    return "admin";
                }
            }
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

            if($result) {
                while ($rowTeam = mysqli_fetch_assoc($result)) {
                    $teams[] = $rowTeam["designation"];
                }
            }

            return $teams;
        }

        public function DeleteTeam($designation) {

        }
        
        public function RenameTeam($oldDesignation, $newDesignation) {
            
        }
                

        /* Team Assignments */
        public function AddUserToTeam($userId, $teamDesignation) {
            $userId = $this->dbConnection->real_escape_string($userId);
            $teamDesignation = $this->dbConnection->real_escape_string($teamDesignation);
            $query = "INSERT INTO team_assignment (team_designation, user_id) VALUES('$teamDesignation', '$userId') ON DUPLICATE KEY UPDATE team_designation='$teamDesignation'";
            $this->dbConnection->query($query);
        }
                
        public function RemoveUserFromTeam ($userEmail, $teamDesignation) {
            
        }

        public function GetAllUsersInTeam($teamDesignation) {
            $teamDesignation = $this->dbConnection->real_escape_string($teamDesignation);
            $query = "SELECT u.mail, u.username FROM user u WHERE u.Id IN (SELECT user_id FROM team_assignment WHERE team_designation = '" . $teamDesignation . "')";
            
            $result = $this->dbConnection->query($query);
            
            $users = array();

            if($result) {
                while ($rowUser = mysqli_fetch_assoc($result)) {
                    $user = new User();
                    $user->Username = $rowUser["username"];
                    $user->Mail = $rowUser["mail"];
                    
                    $users[] = $user;
                }
            }
            return $users;
        }


        /* Event */
        public function AddEvent($designation, $description, $location, $startTime, $meetingLocation, $meetingTime, $seatsRequired, $umpsRequired, $scorerRequired) {
           
            $designation = $this->dbConnection->real_escape_string($designation);
            $description = $this->dbConnection->real_escape_string($description);
            $location = $this->dbConnection->real_escape_string($location);
            $startTime = $this->dbConnection->real_escape_string($startTime);
            $meetingLocation = $this->dbConnection->real_escape_string($meetingLocation);
            $meetingTime = $this->dbConnection->real_escape_string($meetingTime);
            $seatsRequired = $this->dbConnection->real_escape_string($seatsRequired);
            $umpsRequired = $this->dbConnection->real_escape_string($umpsRequired);
            $scorerRequired = $this->dbConnection->real_escape_string($scorerRequired);
           
            $query = "INSERT INTO event(designation, description, location, start_time, meeting_location, meeting_time, qty_seats, qty_umpire, qty_scorer) VALUES ('$designation', '$description','$location','$startTime','$meetingLocation','$meetingTime','$seatsRequired','$umpsRequired','$scorerRequired')";

            $this->dbConnection->query($query);
        }
                
        public function DeleteEvent($eventId) {
            
        }     


        /* Event Assignments */
        public function AddEventToTeam($eventId, $team) {
            $eventId = $this->dbConnection->real_escape_string($eventId);
            $team = $this->dbConnection->real_escape_string($team);
            
            $query = "INSERT INTO event_assignment(event_id, team_designation) VALUES ($eventId, '$team')";

            $this->dbConnection->query($query);
        }
                
        public function RemoveEventFromTeam($eventId, $team) {
            
        }


        /* Event Participation */
        public function AddParticipation($userEmail, $eventId, $accepted, $note, $seats, $isUmpire, $isScorer, $isPlayer, $isCoach) {
            
        }
        
        /* Mail Configuration */
        public function GetMailConfiguration() {
            $query = "SELECT host, username, password, smtp_secure, port, from_address, from_name FROM mail_configuration LIMIT 1";
            $result = $this->dbConnection->query($query);
            $row = $result->fetch_assoc();

            $config = new MailConfiguration();

            $config->Host = $row["host"];
            $config->Username = $row["username"];
            $config->Password = $row["password"];
            $config->SmtpSecure = $row["smtp_secure"];
            $config->Port = $row["port"];
            $config->FromAddress = $row["from_address"];
            $config->FromName = $row["from_name"];

            return $config;
        }
    }
?>