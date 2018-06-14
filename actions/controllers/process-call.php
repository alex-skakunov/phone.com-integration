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

function isCallerPresentInAddressBook($extTo, $callerPhoneNumber) {
    $extensionApi = new Extension;
    
    //$extensionApi->deleteContact(EXTENSION_TO, 2632586);     return true;

    $response = $extensionApi->isCallerPresent($extTo, $callerPhoneNumber);
    if (200 != $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }

    return (int)$data['total'] > 0;
}

function saveCallerToAddressBook($extTo, $callerPhoneNumber) {
    $extensionApi = new Extension;
    $response = $extensionApi->addContact($extTo, $callerPhoneNumber);
    if (!in_array($response->getStatusCode(), array(200, 201))) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . $response->getBody()->getContents());
    }
}

//phpinfo(); exit;
foreach ($mapping as $extensionFrom => $extensionTo) {
    try {
        $callersList = getCallersFromLogs($extensionFrom);
//var_dump($callersList);
//        new dBug(array('list' => $callersList));
        if (empty($callersList)) {
           continue;
        }

        foreach($callersList as $item) {
            $callerPhoneNumber = $item['caller_id'];
            if (strtolower($callerPhoneNumber) == 'private') {
                continue;
            }

            if (isCallerPresentInAddressBook($extensionTo, $callerPhoneNumber)) {
                new dBug(array($callerPhoneNumber => 'already present'));
                continue;
            }
            saveCallerToAddressBook($extensionTo, $callerPhoneNumber);
                query(
                     'INSERT INTO logs VALUES (NULL, :from, :to, :caller, :status, NULL)',
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
                     'INSERT INTO logs VALUES (NULL, :from, :to, :caller, :status, :error)',
                     array(
                         ':from' => $extensionFrom,
                         ':to'   => $extensionTo,
                         ':caller' => $callerPhoneNumber,
                         ':status' => 'fail',
                         ':error'  => $e->getMessage()
                     )
        );
        new dBug($e->getMessage());
        continue;
    }
}
