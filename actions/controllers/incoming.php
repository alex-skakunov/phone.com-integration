<?php

$mapping = array(



9064 => array(
	// => #9065: COX ADD B
      array(
        'extension_to' => 1854653,
        'contact_group' => 388712
      ),

      // => #9088: OT COX CLIENT
      array(
        'extension_to' => 1855889,
        'contact_group' => 388844
      )
),

9077 => array(
	// => #9065: COX ADD B
      array(
        'extension_to' => 1854653,
        'contact_group' => 388712
      ),

      // => #9088: OT COX CLIENT
      array(
        'extension_to' => 1855889,
        'contact_group' => 388844
      )
),


9066 => array(
      //#9043: OT SPEC 1223
      array(
        'extension_to' => 1843686,
        'contact_group' => 388917
      ),

      //#9047: OT SPEC 3753
      array(
        'extension_to' => 1844661,
        'contact_group' => 388918
      ),

      //#9067: SPEC ADD B
      array(
        'extension_to' => 1854656,
        'contact_group' => 388713
      )
),


9078 => array(
      //#9043: OT SPEC 1223
      array(
        'extension_to' => 1843686,
        'contact_group' => 388917
      ),

      //#9047: OT SPEC 3753
      array(
        'extension_to' => 1844661,
        'contact_group' => 388918
      ),

      //#9067: SPEC ADD B
      array(
        'extension_to' => 1854656,
        'contact_group' => 388713
      )
),


9068 => array(
      //#9069: FRON HT ADD B
      array(
        'extension_to' => 1854658,
        'contact_group' => 388714
      ),

      //#9089: OT FRON HT 89015
      array(
        'extension_to' => 1855896,
        'contact_group' => 388848
      ),

      //#9090: OT FRON HT CLIENT 
      array(
        'extension_to' => 1855898,
        'contact_group' => 388852
      )
),


9079 => array(
      //#9069: FRON HT ADD B
      array(
        'extension_to' => 1854658,
        'contact_group' => 388714
      ),

      //#9089: OT FRON HT 89015
      array(
        'extension_to' => 1855896,
        'contact_group' => 388848
      ),

      //#9090: OT FRON HT CLIENT 
      array(
        'extension_to' => 1855898,
        'contact_group' => 388852
      )
),


9070 => array(
      //#9071: FRON LT ADD B
      array(
        'extension_to' => 1854661,
        'contact_group' => 388715
      ),

      //#9091: OT FRON LT 89015
      array(
        'extension_to' => 1855900,
        'contact_group' => 388856
      ),

      //#9092: OT FRON LT CLIENT
      array(
        'extension_to' => 1855902,
        'contact_group' => 388860
      )
),



9080 => array(
      //#9071: FRON LT ADD B
      array(
        'extension_to' => 1854661,
        'contact_group' => 388715
      ),

      //#9091: OT FRON LT 89015
      array(
        'extension_to' => 1855900,
        'contact_group' => 388856
      ),

      //#9092: OT FRON LT CLIENT
      array(
        'extension_to' => 1855902,
        'contact_group' => 388860
      )
),


9072 => array(
      //#9073: ATT ADD B
      array(
        'extension_to' => 1854663,
        'contact_group' => 388716
      ),

      //#9044: OT ATT 8744
      array(
        'extension_to' => 1843687,
        'contact_group' => 388922
      ),

      //#9093: OT ATT LT 8744
      array(
        'extension_to' => 1855903,
        'contact_group' => 388864
      )
),


9081 => array(
      //#9073: ATT ADD B
      array(
        'extension_to' => 1854663,
        'contact_group' => 388716
      ),

      //#9044: OT ATT 8744
      array(
        'extension_to' => 1843687,
        'contact_group' => 388922
      ),

      //#9093: OT ATT LT 8744
      array(
        'extension_to' => 1855903,
        'contact_group' => 388864
      )
),


9074 => array(
      //#9075: CLQ ADD B
      array(
        'extension_to' => 1854665,
        'contact_group' => 388717
      ),

      //#9040: OT CLQ 79041
      array(
        'extension_to' => 1843678,
        'contact_group' => 388923
      ),

      //#9041: OT CLQ 50485
      array(
        'extension_to' => 1843680,
        'contact_group' => 388924
      ),

      //#9046: OT NSTAR 0914
      array(
        'extension_to' => 1843690,
        'contact_group' => 388925
      )
),


9082 => array(
      //#9075: CLQ ADD B
      array(
        'extension_to' => 1854665,
        'contact_group' => 388717
      ),

      //#9040: OT CLQ 79041
      array(
        'extension_to' => 1843678,
        'contact_group' => 388923
      ),

      //#9041: OT CLQ 50485
      array(
        'extension_to' => 1843680,
        'contact_group' => 388924
      ),

      //#9046: OT NSTAR 0914
      array(
        'extension_to' => 1843690,
        'contact_group' => 388925
      )
)
);

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

$callerPhoneNumber = $data['payload']['from_did'];

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

$destinationList = $mapping[$extensionFrom];

if (empty($destinationList)) {
  exit;
}


    try {
        foreach($destinationList as $destination) {
            $extensionTo = $destination['extension_to'];
            $contactGroupId = $destination['contact_group'];

            if (in_array(strtolower($callerPhoneNumber), array('private', 'unknown', ''))) {
                continue;
            }
            if (isCallerPresentInAddressBook($extensionTo, $callerPhoneNumber)) {
                new dBug(array($callerPhoneNumber => 'already present'));
                continue;
            }
            saveCallerToAddressBook($extensionTo, $contactGroupId, $callerPhoneNumber);
                query(
                     'INSERT INTO update_logs VALUES (NULL, :from, :to, :caller, :status, NULL, NOW())',
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
                     'INSERT INTO update_logs VALUES (NULL, :from, :to, :caller, :status, :error, NOW())',
                     array(
                         ':from' => $extensionFrom,
                         ':to'   => $extensionTo,
                         ':caller' => $callerPhoneNumber,
                         ':status' => 'fail',
                         ':error'  => $e->getMessage()
                     )
        );
        new dBug($e->getMessage());
        sendFailEmail('It did not work out to process a caller from call.update event: ' . $e->getMessage());
        continue;
    }
