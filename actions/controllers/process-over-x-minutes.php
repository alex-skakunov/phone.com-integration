<?php

function getCallersFromLogs($ext, $limit=50) {
    $callLogApi = new CallLog;

    $response = $callLogApi->getForExtension($ext, $limit);

    if (200 != $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }

    return $data['items'];
}

function isCallerPresentInAddressBookAndGroup($extTo, $groupId, $callerPhoneNumber) {
    $extensionApi = new Extension;
new dBug(array($extTo, $groupId, $callerPhoneNumber));
    $response = $extensionApi->isCallerPresentInGroup($extTo, $groupId, $callerPhoneNumber);
    if (200 != $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }
new dBug($data);
    return (int)$data['total'] > 0;
}

function saveCallerToAddressBookAndGroup($extTo, $contactGroupId, $callerPhoneNumber) {
    $extensionApi = new Extension;
    $response = $extensionApi->addContactOver7Minutes($extTo, $contactGroupId, $callerPhoneNumber);
    if (!in_array($response->getStatusCode(), array(200, 201))) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . $response->getBody()->getContents());
    }
}

foreach ($durationMapping as $extensionFrom => $destination) {
    try {
        new dBug(array($extensionFrom => $destination));
        $callersList = getCallersFromLogs($extensionFrom, 100);
        new dBug(array('callers list' => $callersList));
        if (empty($callersList)) {
           continue;
        }

            $extensionTo = $destination['extension_to'];
            $contactGroupId = $destination['contact_group'];

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

                if (isCallerPresentInAddressBookAndGroup($extensionTo, $contactGroupId, $callerPhoneNumber)) {
                    new dBug(array($callerPhoneNumber => 'already present'));
                    continue;
                }
                saveCallerToAddressBookAndGroup($extensionTo, $contactGroupId, $callerPhoneNumber);
                    query(
                         'INSERT INTO duration_logs VALUES (NULL, :from, :to, :caller, :status, NULL, NOW())',
                         array(
                             ':from' => $extensionFrom,
                             ':to'   => $extensionTo,
                             ':caller' => $callerPhoneNumber,
                             ':status' => 'success'
                         )
                    );
                    new dBug('Saved');
            }

    }
    catch(Exception $e) {
        query(
                     'INSERT INTO duration_logs VALUES (NULL, :from, :to, :caller, :status, :error, NOW())',
                     array(
                         ':from' => $extensionFrom,
                         ':to'   => $extensionTo,
                         ':caller' => $callerPhoneNumber,
                         ':status' => 'fail',
                         ':error'  => $e->getMessage()
                     )
        );
        new dBug($e->getMessage());
//        sendFailEmail('Could not save the OVER X minutes number ' . $e->getMessage());
        continue;
    }
}
