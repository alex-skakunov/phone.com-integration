<?php

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    error_log('Not a POST request: ' . $_SERVER['REQUEST_METHOD']);
    return;
}

$json = $HTTP_RAW_POST_DATA;
$data = json_decode($json, 1);
if (empty($data['payload'])) {
    error_log('Empty payload for data: ' . $HTTP_RAW_POST_DATA);
    exit;
}

if (empty($data['payload']['call_id'])) {
    error_log('No call_id in data: ' . $HTTP_RAW_POST_DATA);
    exit;
}

query(
    'INSERT INTO `queue` VALUES (NULL, :call_id, DEFAULT, NULL, NOW())',
    array(
        ':call_id' => trim($data['payload']['call_id'])
    )
);