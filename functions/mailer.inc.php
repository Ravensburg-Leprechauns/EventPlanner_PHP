<?php

    include_once './../constants.inc.php';
    include_once ROOT . '/classes/Event.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include_once ROOT . '/functions/dbRepository.php';
    include_once ROOT . '/functions/mailer.inc.php';
    
    require ROOT . '/plugins/PHPMailer/src/PHPMailer.php';
    require ROOT . '/plugins/PHPMailer/src/SMTP.php';
    require ROOT . '/plugins/PHPMailer/src/Exception.php';

    function SendMails($event, $createdBy, $teams) {
        $repo = new DbRepository();
        $mailConfig = $repo->GetMailConfiguration();

        $users = array();

        foreach($teams as $team) {
            $currentTeamUsers = $repo->GetAllUsersInTeam($team);
            foreach($currentTeamUsers as $currentTeamUser) {
                $users[$currentTeamUser->Mail] = $currentTeamUser->Username; 
            }
        }

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            //$mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $mailConfig->Host;
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig->Username;
            $mail->Password = $mailConfig->Password;
            $mail->SMTPSecure = $mailConfig->SmtpSecure;
            $mail->Port = $mailConfig->Port;
        
            //Recipients
            $mail->setFrom($mailConfig->FromAddress, $mailConfig->FromName);

            foreach($users as $mailAddress => $username) {
                $mail->addAddress($mailAddress, $username);
            }

            $mail->isHTML(true);
            $mail->Subject = $event->Designation;
            $mail->Body    = BuildMail($event, $createdBy);
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    function BuildMail($event, $createdBy) {
        $date = new DateTime($event->Time);
        $meetingDate = new DateTime($event->MeetingTime);
        $formattedDate = $date->format('d.m.Y, H:i');
        $formattedMeetingDate = $meetingDate->format('d.m.Y, H:i');

        $text = "$createdBy hat dich zum Event $event->Designation eingeladen.<br/><br/>"
            . "Datum: $formattedDate <br/>Ort: $event->Location<br/>"
            . "Treffpunkt: $formattedMeetingDate ($event->MeetingLocation)<br/><br/>"
            . $event->Description . "<br/><br/>"
            . "Für weitere Informationen und zur Zu- bzw. Absage, melde dich bitte unter " . BASEURL . " an.";
        return $text;
    }

?>