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
        'text' => 'Hmm, something appears to have gone wrong :('
    );

    // check if the username exists
    $stmt = $db->run("SELECT * FROM `users` WHERE `username` = ? AND `email` = ?", 
        [$params['username'], $params['email']]);

    if ($stmt->rowCount() == 1) {

        $row = $stmt->fetch();

        // insert `pass_reset` entry
        $token = bin2hex(random_bytes(16));
        $db->run("INSERT INTO `users_pwd_reset`
            (`user_id`, `recovery_token`, `expire_at`)
            VALUES
            (?, ?, NOW() + INTERVAL 1 DAY)",
            [$row['id'], $token]
        );

        // send reset email
        try {
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
            $mail->addAddress($params['email'], $params['username']);

            // content
            $body = "<p>Hi {$params['username']},<br>use the link in this ";
            $body .= "email to reset your password.</p>";
            $body .= "<p>This link will be active for <b>one day only</b> (until ";
            $body .= date('H:i') . " tomorrow) and can be used to reset your password";
            $body .= " <b>only once</b>!</p>";
            $body .= "--> <a href='https://petervenhuizen.nl/users/reset/{$token}/'>RESET PASSWORD</a> <--";

            $alt_body = "Hi {$params['username']},\nuse the link in this email\n\n";
            $alt_body .= "to reset your password.\n\n";
            $alt_body .= "This link will be active for ONE DAY ONLY (until ";
            $alt_body .= date('H:i') . " tomorrow) and can used to reset your password";
            $alt_body .= "ONLY ONCE!\n\n";
            $alt_body .= "Copy the link below to the internet browser of your choice to reset your password.\n\n";
            $alt_body .= "https://petervenhuizen.nl/users/reset/{$token}/";

            $mail->isHTML(true);
            $mail->Subject = 'Password reset';
            $mail->Body    = $body;
            $mail->AltBody = $alt_body;

            $mail->send();

            $results['class'] = 'success';
            $results['text'] = 'Check your email to reset your password.';
        } catch (Exception $e) {
            $results['text'] = 'Unable to reset your password right now. Please try again later.';
        }

    } else {
        $results['text'] = 'Unable to reset your password.';
    }

    echo json_encode($results);
    exit;
?>