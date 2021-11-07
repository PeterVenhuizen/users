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
    $stmt = $db->run("SELECT * FROM `users_pwd_reset` WHERE `recovery_token` = ?
        AND `expire_at` > NOW()", [$params['token']]);

    if ($stmt->rowCount() == 1) {

        $row = $stmt->fetch();

        // update the password
        $password_hash = password_hash($params['pass-new'], PASSWORD_BCRYPT);
        $db->run("UPDATE `users`
            SET `password` = ? 
            WHERE `id` = ?",
            [$password_hash, $row['user_id']]);

        $results['class'] = 'success';
        $results['text'] = 'Your password has been reset and you can now
            login using your new password.';

        // delete this recovery token and all expired recovery tokens for 
        // good measure
        $db->run("DELETE FROM `users_pwd_reset` WHERE `recovery_token` = ? OR
            `expire_at` < NOW()", [$params['token']]);

    } else {
        $results['text'] = 'Your reset token is incorrect or expired.';
    }

    echo json_encode($results);
    exit;
?>