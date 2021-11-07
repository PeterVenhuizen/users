<?php 
    // include 'config/includes.php'; 
    include 'config/includeFromTop.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="/users/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
    <script defer src="./js/functions.js"></script>
    <script defer src="./js/signup.js"></script>
</head>

<body>
    <div id="app">
        <nav><?php include 'components/nav.php'; ?></nav>
        <main>
            <form action="" method="post" id="register-form" class="center-hover">
                <h2>Register</h2>

                <input type="text" name="invite" hidden>

                <label for="username">Username</label>
                <p>The username may only contain alphanumeric characters (letters 
                    A-Z, number 0-9) and must be between three and 20 characters
                    long.</p>
                <input type="text" name="username">

                <label for="email">Email</label>
                <input type="email" name="email">

                <label for="password">Password</label>
                <p>The password must contain at least one number (0-9), one uppercase 
                    and lowercase letter, and at least 12 or more characters.</p>
                <input type="password" name="password">

                <label for="password-repeat">Confirm password</label>
                <input type="password" name="password-repeat">

                <ul class="feedback hide"></ul>

                <button type="submit" name="submit" class="btn-submit">Sign Up</button>
            </form>
        </main>
    </div>
</body>

</html>