<?php

$queue = new Queue;

$extensionApi = new Extension;
$mapping = $extensionApi->getMappings();

$callLogApi = new CallLog;
foreach ($mapping as $shortExtensionFrom => $destinationList) {
    new dBug(array($shortExtensionFrom => $destinationList));
    try {
        $callersList = $callLogApi->getCallsFromLogs($shortExtensionFrom, '5 minutes ago');
    }
    catch(Exception $e) {
        error_log('[Process call] error: ' . $e);
    }

    if (empty($callersList)) {
       continue;
    }

    new dBug($callersList);

    foreach($destinationList as $shortExtensionTo) {
        foreach($callersList as $item) {
            $callerPhoneNumber = $item['caller_id'];
            if (in_array(strtolower($callerPhoneNumber), array('private', 'unknown', ''))) {
                continue;
            }
            $queue->add($shortExtensionFrom, $shortExtensionTo, EXISTING_ROUTE, $callerPhoneNumber, 'call log', $item);
        }
    }
}