<?php 
    // include 'config/includes.php'; 
    include_once('./config/includeFromTop.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/users/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <div id="app">
        <nav><?php include 'components/nav.php'; ?></nav>
        <main>
            <div class="center-column">
                <h1>404: Page not found</h1>
                <p>Well, this was probably not the page you were looking for ...
                    Sorry about that!
                </p>
                <p>Please check the URL, or visit the <a href="/users/" 
                class="underline">home page</a>.
                </p>
            </div>
        </main>
    </div>
</body>
</html>