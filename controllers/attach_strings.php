<?php
    // include_once('../config/includes.php');
    include_once('../config/includeFromBottom.php');

    $results = array();

    // get the users
    $stmt = $db->run("SELECT `id`, `username`, `privileges`, `registered_on`, 
        `is_active`, `is_blocked` FROM `users`");
    $records = array_map(function($row) { return $row; }, $stmt->fetchAll() );
    $results['users'] = $records;

    // get the invite tokens
    $stmt = $db->run("SELECT `id`, `privileges`, `expire_at`, `uses_left`,
        `used_by` FROM `users_invites`
        ORDER BY `expire_at` DESC");
    $records = array_map(function($row) { return $row; }, $stmt->fetchAll() );
    $results['invites'] = $records;

    echo json_encode($results);
    exit;
?>