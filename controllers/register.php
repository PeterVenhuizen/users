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

    // decrement the invite token uses and track who used it
    $stmt = $db->run("SELECT * FROM `users_invites`");

    // and retrieve the user privileges
    $priv = 0;
    while ($row = $stmt->fetch()) {
        if (password_verify($params['invite'], $row['token'])) {
            $priv = $row['privileges'];
            $updated_used_by = (strlen($row['used_by'])) ? $row['used_by'] . ';' . $params['username'] : $params['username'];

            $q = $db->run("UPDATE `users_invites` SET 
                `uses_left` = `uses_left` - 1, `used_by` = ? 
                WHERE `expire_at` = ? AND `uses_left` > 0",
                [$updated_used_by, $row['expire_at']]
            );
            break;
        }
    }

    // add user to the db
    $status = False;
    $password_hash = password_hash($params['password'], PASSWORD_BCRYPT);
    // $token = md5(rand().time());
    $token = bin2hex(random_bytes(16));

    $status = $db->run("INSERT INTO `users` 
        (`username`, `email`, `password`, `privileges`, `activation_token`, `registered_on`, `is_active`, `is_blocked`)
        VALUES (?, ?, ?, ?, ?, NOW(), '0', '0')",
        [$params['username'], $params['email'], $password_hash, $priv, $token]
    );

    if ($status) {
        $results['class'] = 'success';

        // send activation email
        $from = 'contact@petervenhuizen.nl';

        try {
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
            $body = '<p>Hi <b>' . $params['username'] . '</b>, thanks for signing up!</p>';
            $body .= '<p>Before you can use your account, you need to verify your email address. ';
            $body .= 'Click on the link below to activate your account.</p>';
            $body .= '--> <a href="https://petervenhuizen.nl/users/verify/' . $token . '/">Click Me!</a> <--';

            $alt_body = 'Hi' . $params['username'] . ', thanks for signing up!' . "\n\n";
            $alt_body .= 'Before you can use your account, you need to verify your email address.';
            $alt_body .= 'Copy the link below to your internet browser of choice to activate your account.' . "\n\n";
            $alt_body .= 'https://petervenhuizen.nl/users/verify/' . $token . '/';

            $mail->isHTML(true);
            $mail->Subject = 'Please verify your email';
            $mail->Body    = $body;
            $mail->AltBody = $alt_body;

            $mail->send();

            $results['text'] = 'Registration successful! A verification email has been sent to your email address.';
            
        } catch (Exception $e) {
            $results['class'] = 'warning';
            $results['text'] = "Verification email could not be sent :(. {$mail->ErrorInfo}";
        }

    } else {
        $results['text'] = 'Hmm, something appears to have gone wrong :/ Please try again.';
    }

    echo json_encode($results);
    exit;
?>