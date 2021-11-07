<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    // get the json POST data
    $params = json_decode(file_get_contents('php://input'), true);

    $results = array(
        'class' => 'error',
        'text' => 'Hmm, something appears to have gone wrong :('
    );

    // check if the username exists
    $stmt = $db->run("SELECT * FROM `users` WHERE username = ?", [$params['username']]);

    if ($stmt->rowCount() == 1) {

        $row = $stmt->fetch();

        // check if the password is correct
        if (!password_verify($params['password'], $row['password'])) {
            $results['text'] = 'Incorrect password';
        }

        // check if the user is active
        elseif ($row['is_active'] == '0') {
            $results['class'] = 'warning';
            $results['text'] = 'Account verification is required for login.';
        } 

        // check if user is blocked
        elseif ($row['is_blocked']) {
            $results['class'] = 'warning';
            $results['text'] = "Your account has been blocked";
        }

        // everything actually has gone well :D
        else {
            $results['class'] = 'success';
            $results['text'] = 'Login successful! You will be redirected to your profile page in 3 seconds.';
        }

    } else {
        $results['text'] = 'User account does not exist.';
    }

    echo json_encode($results);
    exit;
?>