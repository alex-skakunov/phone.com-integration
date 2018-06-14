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

if (empty($data['payload']['from_did'])) {
    error_log('No from_did in data: ' . $HTTP_RAW_POST_DATA);
    exit;
}

$extension = $data['payload']['to_did'];

$mappedExtension = $mapping[$extension];
if (empty($mappedExtension) ) {
  error_log('Did not find mapping! ' . $HTTP_RAW_POST_DATA);
  return; //not in our list
}


$callerPhoneNumber = $data['payload']['from_did'];

/*
$extensionApi = new Extension;
$extensionApi->deleteContact(1854208, 2634630);
return;
*/

function isCallerPresentInAddressBook($mappedExtension, $callerPhoneNumber) {
    $extensionApi = new Extension;
    
    //$extensionApi->deleteContact(EXTENSION_TO, 2632586);     return true;

    $response = $extensionApi->isCallerPresent($mappedExtension, $callerPhoneNumber);
    if (200 != $response->getStatusCode()) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }

    return (int)$data['total'] > 0;
}

function saveCallerToAddressBook($mappedExtension, $callerPhoneNumber) {
    $extensionApi = new Extension;
    $response = $extensionApi->addContact($mappedExtension, $callerPhoneNumber);
    if (!in_array($response->getStatusCode(), array(200, 201))) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
    }
}

error_log('Processing item: ' . $callerPhoneNumber);

try {
/*
    if (isCallerPresentInAddressBook($mappedExtension, $callerPhoneNumber)) {
        error_log('Already in the Address book');
        return;
    }
*/
    saveCallerToAddressBook($mappedExtension, $callerPhoneNumber);
    error_log('Saved!');
}
catch(Exception $e) {
    error_log('Error: ' . $e->getMessage());
}