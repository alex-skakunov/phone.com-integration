<?php

saveCallerToAddressBook('1843678', '389434', '+12063348569');
saveCallerToAddressBook('1843678', '389434', '+12524128529');
saveCallerToAddressBook('1843678', '389434', '+15052283514');
saveCallerToAddressBook('1843678', '389434', '+12129560029');
saveCallerToAddressBook('1843678', '389434', '+12129560029');
saveCallerToAddressBook('1843678', '389434', '+12129560029');
saveCallerToAddressBook('1843680', '388924', '+13193607070');
saveCallerToAddressBook('1843680', '388924', '+17024655998');
saveCallerToAddressBook('1843680', '388924', '+13345899833');
saveCallerToAddressBook('1843680', '388924', '+17024937479');
saveCallerToAddressBook('1843680', '388924', '+17024937479');
saveCallerToAddressBook('1843680', '388924', '+12405209443');
saveCallerToAddressBook('1843680', '388924', '+12405209443');
saveCallerToAddressBook('1843686', '388917', '+16166338360');
saveCallerToAddressBook('1843686', '388917', '+12053255070');
saveCallerToAddressBook('1843686', '388917', '+16166338360');
saveCallerToAddressBook('1843686', '388917', '+12053255070');
saveCallerToAddressBook('1843686', '388917', '+16166338360');
saveCallerToAddressBook('1843686', '388917', '+13219452586');
saveCallerToAddressBook('1843690', '388925', '+19192724422');
saveCallerToAddressBook('1843690', '388925', '+13369571474');
saveCallerToAddressBook('1843690', '388925', '+19132280439');
saveCallerToAddressBook('1843690', '388925', '+19192724422');
saveCallerToAddressBook('1843690', '388925', '+19192724422');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+13143091998');
saveCallerToAddressBook('1844661', '388918', '+13143091998');
saveCallerToAddressBook('1844661', '388918', '+12053254771');
saveCallerToAddressBook('1844661', '388918', '+18645855382');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1844661', '388918', '+18643160764');
saveCallerToAddressBook('1854653', '388712', '+17022352516');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+18642790847');
saveCallerToAddressBook('1854656', '388713', '+18642790847');
saveCallerToAddressBook('1854656', '388713', '+18642790847');
saveCallerToAddressBook('1854656', '388713', '+12549813327');
saveCallerToAddressBook('1854656', '388713', '+12549813327');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854656', '388713', '+14143771183');
saveCallerToAddressBook('1854665', '388717', '+13194318311');
saveCallerToAddressBook('1854665', '388717', '+13194318311');
saveCallerToAddressBook('1854665', '388717', '+13194318311');
saveCallerToAddressBook('1854665', '388717', '+16512198281');
saveCallerToAddressBook('1854665', '388717', '+16512198281');
saveCallerToAddressBook('1855889', '388844', '+17023027676');


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

function saveCallerToAddressBook($extTo, $contactGroupId, $callerPhoneNumber) {
    $extensionApi = new Extension;
    $response = $extensionApi->addContact($extTo, $contactGroupId, $callerPhoneNumber);
    if (!in_array($response->getStatusCode(), array(200, 201))) {
        throw new Exception($response->getReasonPhrase());
    }

    $data = json_decode($response->getBody()->getContents(), 1);
    if (empty($data)) {
        throw new Exception('Could not convert the JSON: ' . $response->getBody()->getContents());
    }
}

