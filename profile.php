<?php 
    // include 'config/includes.php'; 
    include_once('./config/includeFromTop.php');

    if (!$_SESSION['is_logged_in']) {
        header("Location: /users/");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/users/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
    <script defer src="./js/functions.js"></script>
    <script defer src="./js/profile.js"></script>
</head>
<body>
    <div id="app">
        <nav><?php include 'components/nav.php'; ?></nav>
        <main>

            <div class="center-column">
                <div>
                    <h2>Account settings</h2>
                    <hr>
                    <div class="setting-group">
                        <div class="text">
                            <h5>Username</h5>
                            <span id="logged_in_user"><?php echo $_SESSION['username']; ?></span>
                        </div>
                        <button class="btn-small btn-primary">Change</button>

                        <div class="form-elements">
                            <h2>Update your username</h2>

                            <p>The username may only contain alphanumeric characters (letters 
                                A-Z, number 0-9) and must be between three and 20 characters
                                long.</p>

                            <input type="text" name="username" placeholder="New username">

                            <ul class="feedback hide"></ul>

                            <button type="submit" name="submit" class="btn-submit" data-update="username">Update</button>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="text">
                            <h5>Email address</h5>
                            <span><?php echo $_SESSION['email']; ?></span>
                        </div>
                        <button class="btn-small btn-primary">Change</button>

                        <div class="form-elements">
                            <h2>Update your email</h2>

                            <p>There will be a new verification email sent that you
                                will need to use to verify this new email address.</p>

                            <input type="email" name="email" placeholder="New email" autocomplete="off">

                            <input type="password" name="password" placeholder="Current password">

                            <ul class="feedback hide"></ul>

                            <button type="submit" name="submit" class="btn-submit" data-update="email">Update</button>
                        </div>
                    </div>

                    <div class="setting-group">
                        <div class="text">
                            <h5>Change password</h5>
                        </div>
                        <button class="btn-small btn-primary">Change</button>
                        <div class="form-elements">
                            <h2>Update your password</h2>

                            <p>The password must contain at least one number (0-9), one uppercase 
                                and lowercase letter, and at least 12 or more characters.</p>

                            <input type="password" name="pass-old" placeholder="Current password">

                            <input type="password" name="pass-new" placeholder="New password">

                            <input type="password" name="pass-confirm" placeholder="Confirm new password">

                            <ul class="feedback hide"></ul>

                            <button type="submit" name="submit" class="btn-submit" data-update="password">Update</button>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <div id="modal-wrapper">
        <div class="modal">
            <i class="fas fa-times modal-close"></i>
            <form autocomplete="off"></form>
        </div>
    </div>

</body>
</html>