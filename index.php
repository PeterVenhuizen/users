<?php 
    // include 'config/includes.php'; 
    include './config/includeFromTop.php';

    if ($_SESSION['is_logged_in']) {
        header("Location: profile/");
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
    <script defer src="./js/functions.js"></script>
    <script defer src="./js/login.js"></script>
</head>

<body>
    <div id="app">
        <nav><?php include 'components/nav.php'; ?></nav>
        <main>
            <form action="" method="" id="login-form" class="center-hover">
                <h2>Login</h2>

                <label for="username">Username</label>
                <input type="text" name="username">

                <label for="password">Password</label>
                <input type="password" name="password">

                <!-- <div class="form-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div> -->

                <a href="forgotpassword/" class="underline">Forgot password?</a>

                <ul class="feedback hide"></ul>

                <button type="submit" name="submit" class="btn-submit">Sign In</button>
            </form>
        </main>
    </div>
</body>

</html>