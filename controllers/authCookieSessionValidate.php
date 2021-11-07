<?php
    // get the current date
    $current_time = time();
    $current_date = date("Y-m-d H:i:s", $current_time);

    // set the expiry date for 1 month from now
    $cookie_expiration_time = $current_time + (7 * 24 * 60 * 60); // now + 1 week

    $is_logged_in = False;

    if (!empty($_SESSION['is_logged_in'])) {
        $is_logged_in = True;
    }

    // check if remember me cookie exists
    elseif (!empty($_COOKIE['user_login']) && !empty($_COOKIE['random_pwd']) && !empty($_COOKIE['random_selector'])) {

        // initiate token validation as false
        $is_pwd_verified = False;
        $is_selector_verified = False;
        $is_expiry_verified = False;

        // get the user token data
        $userToken = $auth->getTokenByUsername($_COOKIE['user_login']);

        // validate the random password cookie
        if (password_verify($_COOKIE['random_pwd'], $userToken['password_hash'])) {
            $is_pwd_verified = True;
        }

        // validate the random selector cookie
        if (password_verify($_COOKIE['random_selector'], $userToken['selector_hash'])) {
            $is_selector_verified = True;
        }

        // check cookie expiration date
        if ($userToken['expiry_date'] > $current_date) {
            $is_expiry_verified = True;
        }

        // redirect if everything is true
        if ($is_pwd_verified && $is_selector_verified && $is_expiry_verified) {
            $_SESSION['is_logged_in'] = True;
            $_SESSION['username'] = $_COOKIE['user_login'];
            $is_logged_in = True;

        // otherwise clean up
        } else {
            // get rid of the expired token
            $auth->deleteExpired($userToken['id']);

            // clear cookies
            $util->clearAuthCookies();
        }

    } 
?>