<?php
$api = new Extension;
$contents = file_get_contents('/var/www/html/ani-route2/actions/controllers/fix.json');
$data = json_decode($contents, 1);

$groupsList = array();

foreach($data['items'] as $item) {
    $groupId = $item['id'];
    if (in_array($groupId, array(389434, 388923))) {
        continue;
    }
    // query('INSERT IGNORE INTO _groups_to_kill (`id`, `extension_id`) VALUES('.$groupId.', 1843678)');
    $groupsList[] = $groupId;
}
sort($groupsList);

foreach($groupsList as $groupId) {
    $response = $api->getContactsOfGroup(1843678, $groupId, 500);
    $rawNumbers = json_decode($response->getBody()->getContents(), 1);
    $numbersList = array();
    foreach($rawNumbers['items'] as $numberRecord) {
        $contactId = $numberRecord['id'];
        $phoneNumber = $numberRecord['phone_numbers'][0]['normalized'];
        query('INSERT IGNORE INTO _group_contacts VALUES('.$contactId.', '.$groupId.', "'.$phoneNumber.'", 9040)');
    }
}

new dBug($groupsList);