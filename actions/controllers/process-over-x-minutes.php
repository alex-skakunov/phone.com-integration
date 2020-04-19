<?php

$queue = new Queue;

$extensionApi = new Extension;
$durationMapping = $extensionApi->getDurationMappings();

$callLogApi = new CallLog;

foreach ($durationMapping as $shortExtensionFrom => $destinationList) {
    // new dBug(array($shortExtensionFrom => $destinationList));

    try {
        $callersList = $callLogApi->getCallsFromLogs($shortExtensionFrom, '10 minute ago');
    }
    catch(Exception $e) {
        error_log('[Process call] error: ' . $e);
        continue;
    }

    // new dBug(array('callers list' => $callersList));
    
    if (empty($callersList)) {
       continue;
    }

    foreach($destinationList as $shortExtensionTo) {
        foreach($callersList as $item) {
            $callerPhoneNumber = $item['caller_id'];
            if (in_array(strtolower($callerPhoneNumber), array('private', 'unknown', ''))) {
                continue;
            }

            $callDuration = (int)$item['call_duration'];
            if ($callDuration < 420) {
               new dBug('Too short, skipping');
                continue;
            }

            $queue->add($shortExtensionFrom, $shortExtensionTo, OVER_X_MINUTES, $callerPhoneNumber, 'duration log', $item);
        }
    }

}
