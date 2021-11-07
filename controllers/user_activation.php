<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    // get the json POST data
    $params = json_decode(file_get_contents('php://input'), true);

    $results = array(
        'class' => 'error',
        'text' => 'Hmm, something appears to have gone wrong :('
    );

    // see if the token exists
    $stmt = $db->run("SELECT is_active FROM `users` WHERE activation_token = ?", 
        [$params['token']]);

    // activate account
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        if ($row['is_active'] == 0) {

            // set is_active to true
            $status = $db->run("UPDATE `users` SET is_active = '1' WHERE activation_token = ?", [$params['token']]);
            if ($status) {
                $results['class'] = 'success';
                $results['text'] = 'Your email has been successfully verified. You can now use your account to login.';                
            }

        } else {
            $results['class'] = 'warning';
            $results['text'] = 'Your account has already been activated.';
        }
    } else {
        $results['text'] = 'Unable to verify your account with this token.';
    }

    echo json_encode($results);
    exit;
?>