<?php

$totalCount = query('SELECT COUNT(*) AS "cnt" FROM `queue`')->fetchColumn();
$queuedCount = query('SELECT COUNT(*) AS "cnt" FROM `queue` WHERE status="queued"')->fetchColumn();

if (empty($_POST)) {
    return;
}

if (!empty($_POST['user_submit'])) {
    $newPassword = trim($_POST['user_password']);
    if (empty($newPassword)) {
        $errorMessage = 'The password should not be empty';
        return;
    }
    query('UPDATE `settings` SET `value`=:password WHERE `name`="password"', array(':password' => $newPassword));
    $message = 'The password has been successfully updated';
}
