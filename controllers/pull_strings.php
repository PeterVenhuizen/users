<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    // get the json POST data
    $params = json_decode(file_get_contents('php://input'), true);

    $results = array(
        'class' => 'error',
        'text' => 'Something went wrong'
    );

    switch ($params['action']) {
        case 'new-invite':
            $token = bin2hex(random_bytes(16));
            $token_hash = password_hash($token, PASSWORD_BCRYPT);

            $status = $db->run("INSERT INTO `users_invites`
                (`token`, `privileges`, `expire_at`, `uses_left`)
                VALUES (?, ?, ?, ?)",
                [$token_hash, $params['rights'], $params['expire_at'], $params['n_uses']]);
                       
            if ($status) {
                $results['text'] = "Invite created successfully. 
                Please store the token before you refresh the page. You will 
                NOT be able to retrieve it later!\nToken: \"{$token}\"";
            }
            break;

        case 'block-user':
            if ($_SESSION['user_id'] != $params['id']) {
                $status = $db->run("UPDATE `users` SET `is_blocked` = NOT `is_blocked` WHERE `id` = ?", [$params['id']]);
            }
            break;

        case 'update-rights':

            // check if there is an admin-level user left after this
            $stmt = $db->run("SELECT `id` FROM `users` WHERE `privileges` = 3");
            $admins = array_map(function($row) { return $row['id']; }, $stmt->fetchAll());

            if (count($admins) > 1 || $params['rights'] == 3 || !in_array($params['id'], $admins)) {
                $status = $db->run("UPDATE `users` SET `privileges` = ? 
                    WHERE `id` = ? AND `username` = ?",
                    [$params['rights'], $params['id'], $params['username']]);
                $results['text'] = "The rights for \"{$params['username']}\"
                    were updated. Feel free to refresh the page.";

                // change the session if this affected the logged in user
                if ($_SESSION['user_id'] == $params['id']) {
                    $_SESSION['privileges'] = $params['rights'];
                }
            } else {
                $status = false;
                $results['text'] = "Sorry, but I can't take your rights away. 
                    We need at least one admin-level user in the system.";
            }

            break;

        case 'disable-invite':
            $status = $db->run("UPDATE `users_invites` SET `uses_left` = '0' WHERE `id` = ?", [$params['id']]);
            break;

        case 'delete-invite':
            $status = $db->run("DELETE FROM `users_invites` WHERE `id` = ?", [$params['id']]);
            break;
    }

    if ($status) $results['class'] = 'success';

    echo json_encode($results);
    exit;
?>