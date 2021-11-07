<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    if (isset($_POST['username']) && isset($_POST['password'])) {

        // check if the username exists
        $stmt = $db->run("SELECT * FROM `users` WHERE username = ?", [$_POST['username']]);

        if ($stmt->rowCount() == 1) {

            $row = $stmt->fetch();

            // check if the password is correct
            if (password_verify($_POST['password'], $row['password'])) {
                // header("Location: /users/profile/");
                $_SESSION['is_logged_in'] = True;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['privileges'] = $row['privileges'];

                // Set auth cookies if 'Remember me' was checked
                // if (isset($_POST['remember'])) {

                //     setcookie('user_login', $row['username'], $cookie_expiration_time, './', '.localhost/');

                //     $random_pwd = $util->getToken(32);
                //     setcookie('random_pwd', $random_pwd, $cookie_expiration_time, './', '.localhost/');

                //     $random_selector = $util->getToken(32);
                //     setcookie('random_selector', $random_selector, $cookie_expiration_time, './', '.localhost/');

                //     // remove existing token
                //     $userToken = $db->run("SELECT * FROM `tbl_token_auth` WHERE username = ? LIMIT 1", [$row['username']])->fetch();
                //     if (!empty($userToken['id'])) {
                //         $db->run("DELETE FROM `tbl_token_auth` WHERE id = ?", [(int)$userToken['id']]);
                //     }

                //     // insert new token
                //     $random_pwd_hash = password_hash($random_pwd, PASSWORD_BCRYPT);
                //     $random_selector_hash = password_hash($random_selector, PASSWORD_BCRYPT);
                //     $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
                //     $db->run("INSERT INTO `tbl_token_auth`
                //         (username, password_hash, selector_hash, expiry_date)
                //         VALUES (?, ?, ?, ?)
                //     ", [$row['username'], $random_pwd_hash, $random_selector_hash, $expiry_date]);

                // } else {
                //     $util->clearAuthCookies();
                // }

                // $util->redirect('/users/profile/');
                header("Location: /{$_POST['redirectTo']}");

            }
        
        }

    } else {
        header("Location: /users/");
        // $util->redirect('/users/');
    }

?>