<?php
    // include 'config/includes.php';
    include './config/includeFromTop.php';

    if (!$_SESSION['is_logged_in'] || $_SESSION['privileges'] < 3) {
        http_response_code(404);
        include '404.php';
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/users/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woodshop</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/puppeteer.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous">
    </script>
    <script defer src="./js/functions.js"></script>
    <script defer src="./js/puppeteer.js"></script>
</head>
<body>
    <div id="app">
        <nav><?php include 'components/nav.php'; ?></nav>
        <main>

            <div class="center-column">

                <div>
                    <h2>Manage users</h2>
                    <hr>
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Rights</th>
                                <th>Register on</th>
                                <th>Is active?</th>
                                <th>Is blocked?</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-users"></tbody>
                    </table>
                    <div class="form-elements">
                        <h2>What rights should <span class="username"></span> get?</h2>

                        <input type="text" name="id" hidden>
                        <input type="text" name="username" hidden>

                        <label for="rights">User rights: </label>
                        <p>Set the rights granted for users using this invite.<br>
                            0: View books and full Sinterklaas CRUD<br>
                            1: Books CRUD<br>
                            2: Dunno yet<br>
                            3: Admin rights<br>
                        </p>
                        <select name="rights" class="rights">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>

                        <ul class="feedback hide"></ul>

                        <button type="submit" name="submit" class="btn-submit" data-action="update-rights">Update rights</button>
                    </div>
                </div>

                <div>
                    <h2>Manage invites</h2>
                    <hr>
                    <table>
                        <thead>
                            <tr>
                                <th>Rights</th>
                                <th>Expires on</th>
                                <th>Uses left</th>
                                <th>Used by</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-invites"></tbody>
                    </table>

                    <span id="create-new-invite" class="underline">Create new invite</span>
                    <div class="form-elements">
                        <h2>Create new invite</h2>

                        <label for="rights">User rights: </label>
                        <p>Set the rights granted for users using this invite.<br>
                            0: View books and full Sinterklaas CRUD<br>
                            1: Books CRUD<br>
                            2: Dunno yet<br>
                            3: Admin rights<br>
                        </p>
                        <select name="rights" class="rights">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>

                        <label for="expire_at">Expiration date: </label>
                        <p>Determine until when this invite can be used</p>
                        <input type="date" name="expire_at" 
                            min="<?php echo date('Y-m-d', strtotime("+1 day")); ?>"
                            value="<?php echo date('Y-m-d', strtotime("+1 day")); ?>"
                        >

                        <label for="n_uses">Number of uses: </label>
                        <input type="number" name="n_uses" min="1" max="999" value="1">

                        <ul class="feedback hide"></ul>

                        <button type="submit" name="submit" class="btn-submit" data-action="new-invite">Create invite</button>
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