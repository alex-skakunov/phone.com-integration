<?php

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    return;
}

$json = $HTTP_RAW_POST_DATA;
$data = json_decode($json, 1);
if (empty($data['payload'])) {
    exit;
}

if (empty($data['payload']['from_did'])) {
    exit;
}

$extensionFrom = $data['payload']['to_extn'];

$callerPhoneNumber = strtolower(trim($data['payload']['from_did']));
if (in_array($callerPhoneNumber, array('private', 'unknown', ''))) {
    exit;
}

$api = new Extension;
$mappingList = $api->getMappingByShortExtension($extensionFrom);

if (empty($mappingList)) {
  exit;
}

$queue = new Queue;
foreach($mappingList as $mapping) {
    $shortExtensionTo = $mapping['to_extension'];
    $queue->add($extensionFrom, $shortExtensionTo, EXISTING_ROUTE, $callerPhoneNumber, 'call.update', $json);
}