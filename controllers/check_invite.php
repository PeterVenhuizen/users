<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    // get the json POST data
    $params = json_decode(file_get_contents('php://input'), true);

    $results = array(
        'class' => 'error',
        'text' => 'We could not validate your registration token. Please try again.'
    );

    // get all tokens
    $stmt = $db->run("SELECT * FROM `users_invites`");

    // check all tokens
    while ($row = $stmt->fetch()) {

        // check each token
        if (password_verify($params['token'], $row['token'])) {

            // check if it hasn't expired
            $current_time = time();
            $current_date = date("Y-m-d H:i:s", $current_time);
            if ($row['expire_at'] > $current_date && $row['uses_left'] > 0) {
                $results['class'] = 'success';
                $results['text'] = 'Your registration token is valid. Please proceed by filling the form.';
            } else {
                $results['text'] = 'Your registration token has expired.';
            }
            break;

        } else {
            $results['text'] = 'Your registration token is invalid.';
        }

    }

    echo json_encode($results);
    exit;
?>