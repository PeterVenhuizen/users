<?php
    // PHPMailer requirements
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require_once '../vendor/PHPMailer/src/Exception.php';
    require_once '../vendor/PHPMailer/src/PHPMailer.php';
    require_once '../vendor/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    // get the json POST data
    $params = json_decode(file_get_contents('php://input'), true);

    $results = array(
        'class' => 'error',
        'text' => 'Hmm, something went wrong. Please try again'
    );

    switch ($params['what']) {
        case 'username':
            $status = $db->run("UPDATE `users` 
                SET `username` = ?
                WHERE `id` = ? AND `username` = ?",
                [$params['username'], $_SESSION['user_id'], $_SESSION['username']]);
            
            if ($status) {
                $_SESSION['username'] = $params['username'];
                $results['text'] = "Your username was successfully updated. 
                    This page will automatically refresh in 3 seconds.";
            }
            break;
        
        case 'email':
            // generate a new activation token
            $token = bin2hex(random_bytes(16));

            $status = $db->run("UPDATE `users` 
                SET `email` = ?, `activation_token` = ?, `is_active` = '0'
                WHERE `id` = ? AND `username` = ?",
                [$params['email'], $token, $_SESSION['user_id'], $_SESSION['username']]);

            if ($status) {
                $_SESSION['email'] = $params['email'];
                $results['text'] = "Your email was successfully updated. A new 
                    verification email has been sent to your updated email.
                    You will be automatically logged out in 5 seconds.";
            }

            // send activation email
            $from = 'contact@petervenhuizen.nl';

            $mail->isSMTP();
            $mail->Host       = 'mail.mijndomein.nl';
            $mail->SMTPAuth   = true;
            $mail->Username   = $from;
            $mail->Password   = 'EQ6p*RGt*u&LtaQP';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // recipients
            $mail->setFrom($from, 'petervenhuizen.nl');
            $mail->addAddress($params['email'], $params['logged_in_user']);

            // content
            $body = '<p>Hi <b>' . $params['logged_in_user'] . '</b>,<br>you receive this email because your email address has been changed.<br>';
            $body .= 'If this was not you, we suggest you immediately change your password!</p>';
            $body .= '<p>If this was you, please click on the activation link to verify your new email address.</p>';
            $body .= '--> <a href="https://petervenhuizen.nl/users/verify/'.$token.'/">Click here to verify and activate your account!</a> <--';

            $alt_body = "Hi {$params['logged_in_user']},\n you receive this email your email address has been changed.\n";
            $alt_body .= "If this was not you, we suggest you immediately change your password!\n\n";
            $alt_body .= 'If this was you, copy the link below to your internet browser of choice to activate your account.' . "\n\n";
            $alt_body .= 'https://petervenhuizen.nl/users/verify/' . $token . '/';

            $mail->isHTML(true);
            $mail->Subject = 'Your email has been changed';
            $mail->Body    = $body;
            $mail->AltBody = $alt_body;

            $mail->send();
            break;

        case 'password':
            $password_hash = password_hash($params['pass-new'], PASSWORD_BCRYPT);
            $status = $db->run("UPDATE `users`
                SET `password` = ?
                WHERE `id` = ? AND `username` = ?",
                [$password_hash, $_SESSION['user_id'], $_SESSION['username']]);

            if ($status) {
                $results['text'] = "Your password was updated successfully! 
                    You will be automatically logged out in 5 seconds.";
            }
            break;
    }

    if ($status) $results['class'] = 'success';

    echo json_encode($results);
    exit;
?>