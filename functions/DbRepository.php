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
            $query = "SELECT id, is_admin, username, password FROM user WHERE mail = '$mail'";
            $result = $this->dbConnection->query($query);
            $row = $result->fetch_assoc();
            
            if($result->num_rows === 0 || !password_verify($password , $row["password"])) {
                return false;
            } else {

                $isAdmin = $row['is_admin'];
                $username = $row['username'];

                $_SESSION["userid"] = $row["id"];
    
                if($isAdmin == 0 || $isAdmin == 1) {
                    $_SESSION["username"] = $username;

                    $teamDesignation = $this->GetTeamForUser($row['id']);
                    
                    if($isAdmin == 0) {
                        $_SESSION["usertype"] = "user";
                    } else {
                        $_SESSION["usertype"] = "admin";
                    }

                    $_SESSION["team"] = $teamDesignation;
                    
                    return true;
                } 
            }
        }

        public function GetTeamForUser($userId) {
            $userId = $this->dbConnection->real_escape_string($userId);
            $query = "SELECT team_designation FROM team_assignment WHERE user_id = $userId";

            $result = $this->dbConnection->query($query);
            $row = $result->fetch_assoc();

            // TODO Check if no team is assigned
            return $row['team_designation'];
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

        public function GetAllEvents($team) {
            $team = $this->dbConnection->real_escape_string($team);

            $query = "SELECT * FROM event e, EVENT_ASSIGNMENT a WHERE e.Id = a.EVENT_ID AND a.TEAM_DESIGNATION = '$team'";

            $result = $this->dbConnection->query($query);
            
            $events = array();

            if($result) {
                while ($rowEvent = mysqli_fetch_assoc($result)) {
                    $event = new Event();
                    $event->Designation = $rowEvent["designation"];
                    $event->Description = $rowEvent["description"];
                    $event->Location = $rowEvent["location"];
                    $event->Time = $rowEvent["start_time"];
                    $event->MeetingLocation = $rowEvent["meeting_location"];
                    $event->MeetingTime = $rowEvent["meeting_time"];
                    $event->SeatsRequired = $rowEvent["qty_seats"];
                    $event->ScorerRequired = $rowEvent["qty_scorer"];
                    $event->UmpiresRequired = $rowEvent["qty_umpire"];
                    
                    $events[] = $event;
                }
            }
            return $events;
        }

        public function GetAllNewEvents($userId, $team) {
            $team = $this->dbConnection->real_escape_string($team);
            
            $query = "SELECT * FROM event e, EVENT_ASSIGNMENT a WHERE e.Id = a.EVENT_ID AND a.TEAM_DESIGNATION = '$team' AND e.ID NOT IN (SELECT event_id FROM event_participation WHERE user_id = $userId)";
            
            $result = $this->dbConnection->query($query);
                        
            $events = array();
            
            if($result) {
                while ($rowEvent = mysqli_fetch_assoc($result)) {
                    $event = new Event();
                    $event->Designation = $rowEvent["designation"];
                    $event->Description = $rowEvent["description"];
                    $event->Location = $rowEvent["location"];
                    $event->Time = $rowEvent["start_time"];
                    $event->MeetingLocation = $rowEvent["meeting_location"];
                    $event->MeetingTime = $rowEvent["meeting_time"];
                    $event->SeatsRequired = $rowEvent["qty_seats"];
                    $event->ScorerRequired = $rowEvent["qty_scorer"];
                    $event->UmpiresRequired = $rowEvent["qty_umpire"];
                                
                    $events[] = $event;
                    }
                }
                return $events;
        }

        public function GetAllUnfinishedEvents() {

            $query = "SELECT * FROM event WHERE start_time > NOW()";
            
            $result = $this->dbConnection->query($query);
                        
            $events = array();
            
            if($result) {
                while ($rowEvent = mysqli_fetch_assoc($result)) {
                    $event = new Event();
                    $event->Id = $rowEvent["id"];
                    $event->Designation = $rowEvent["designation"];
                    $event->Description = $rowEvent["description"];
                    $event->Location = $rowEvent["location"];
                    $event->Time = $rowEvent["start_time"];
                    $event->MeetingLocation = $rowEvent["meeting_location"];
                    $event->MeetingTime = $rowEvent["meeting_time"];
                    $event->SeatsRequired = $rowEvent["qty_seats"];
                    $event->ScorerRequired = $rowEvent["qty_scorer"];
                    $event->UmpiresRequired = $rowEvent["qty_umpire"];
                                
                    $events[] = $event;
                }
            }
            return $events;
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
        
        public function GetEventParticipations($event) {

            $eventParticipations = array();

            $query = "SELECT ep.accepted, ep.note, ep.seats, ep.is_umpire, ep.is_scorer, ep.is_player, ep.is_coach, u.username
                 FROM event_participation ep, user u  WHERE event_id = $event->Id AND u.Id = ep.user_id";

            $result = $this->dbConnection->query($query);

            if($result) {
                while ($rowEvent = mysqli_fetch_assoc($result)) {
                    $eventParticipation = new EventParticipation();
                    $eventParticipation->Event = $event;
                    $eventParticipation->Username = $rowEvent["username"];
                    $eventParticipation->Accepted = $rowEvent["accepted"];
                    $eventParticipation->Note = $rowEvent["note"];
                    $eventParticipation->Seats = $rowEvent["seats"];
                    $eventParticipation->IsUmpire = $rowEvent["is_umpire"];
                    $eventParticipation->IsScorer = $rowEvent["is_scorer"];
                    $eventParticipation->IsPlayer = $rowEvent["is_player"];
                    $eventParticipation->IsCoach = $rowEvent["is_coach"];
        
                    $eventParticipations[] = $eventParticipation;
                }
            }
            return $eventParticipations;
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

        /* News */
        public function AddNews($text) {
            $text = $this->dbConnection->real_escape_string($text);
            
            $query = "INSERT INTO news(text) VALUES ('$text')";

            $this->dbConnection->query($query);
        }

        public function GetLatestNews() {
            $query = "SELECT text FROM news WHERE id = (SELECT MAX(id) FROM news)";

            $result = $this->dbConnection->query($query);

            $row = $result->fetch_assoc();

            $text = $row["text"];
            if(!isset($text) || trim($text) === '') {
                return "Keine Neuigkeiten vorhanden";
            } else {
                return $text;
            }
        }
    }
?>