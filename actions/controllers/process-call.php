<?php

function getCallerFromLogs($item) {
    $callLogApi = new CallLog;
    query('UPDATE queue SET status="processing", error_message=NULL, processing_time=NULL WHERE id=' . $item['id']);

    $response = $callLogApi->get($item['call_id']);

    if (200 != $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }

    return trim($data['caller_id']);
}

function isCallerPresentInAddressBook($item, $callerPhoneNumber) {
    $extensionApi = new Extension;
    
    $extensionApi->deleteContact(EXTENSION_TO, 2632586);     return true;

    $response = $extensionApi->isCallerPresent(EXTENSION_TO, $callerPhoneNumber);
    if (200 != $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }

    return (int)$data['total'] > 0;
}

function saveCallerToAddressBook($item, $callerPhoneNumber) {
    $extensionApi = new Extension;
    $response = $extensionApi->addContact(EXTENSION_TO, $callerPhoneNumber);
    if (!in_array($response->getStatusCode(), array(200, 201))) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }
}

$items = query('SELECT * FROM queue WHERE status="queued" LIMIT 10')->fetchAll();
if (empty($items)) {
    return;
}

foreach ($items as $item) {
    $startTime = microtime_float();
    try {
        $callerPhoneNumber = getCallerFromLogs($item);
        if (!isCallerPresentInAddressBook($item, $callerPhoneNumber)) {
            saveCallerToAddressBook($item, $callerPhoneNumber);
        }
        $endTime = microtime_float();
        query(
            'UPDATE queue SET status="success", error_message=NULL, processing_time=:duration WHERE id=' . $item['id'],
            array(':duration' => $endTime-$startTime)
        );
    }
    catch(Exception $e) {
        $endTime = microtime_float();
        query(
            'UPDATE queue SET status="error", error_message=:message, processing_time=:duration WHERE id=' . $item['id'],
            array(
                ':message' => $e->getMessage(),
                ':duration' => $endTime-$startTime
            )
        );
        continue;
    }
}