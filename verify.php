<?php 
    // include 'config/includes.php'; 
    include './config/includeFromTop.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="/users/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Verification</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
    <script defer src="./js/functions.js"></script>
    <script defer src="./js/verify.js"></script>
</head>

<body>
    <div id="app">
        <nav><?php include 'components/nav.php'; ?></nav>
        <main>
            <div class="center-hover">
                <h2>User Verification</h2>
                <div class="feedback hide"></div>
                <a href="/users/" class="underline hide">Click to Login</a>
            </div>
        </main>
    </div>
</body>

</html>