<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    // get the json POST data
    $params = json_decode(file_get_contents('php://input'), true);

    $results = array(
        'class' => 'error',
        'text' => "This username is unavailable."
    );

    // check if the username is already in use
    $stmt = $db->run("SELECT * FROM `users` WHERE username = ?", [$params['username']]);
    if ($stmt->rowCount() == 0) $results['class'] = 'success';

    echo json_encode($results);
    exit;
?>