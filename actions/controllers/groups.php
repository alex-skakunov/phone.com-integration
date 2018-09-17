<?php
include_once 'classes/GroupsQueue.php';

$queue = new GroupsQueue;
$jobs = $queue->fetch(5, 'RAND()');

$extensionApi = new Extension;

foreach ($jobs as $job) {
    $start = microtime_float();
    new dBug($job);

    $jobId = $groupId =  $job['id'];
    $extensionId = $job['extension_id'];
    $queue->setStatusProcessing($jobId);

    try {
            new dBug(array('extension' => $extensionId));
            $response = $extensionApi->getContactsOfGroup($extensionId, $groupId);

            $data = json_decode($response->getBody()->getContents(), 1);
            new dBug($data);

            query('UPDATE `_groups` SET total_contacts = '.(int)$data['total'] . ' WHERE id='.$jobId); 

            foreach ($data['items'] as $contact) {
		query('INSERT IGNORE INTO _group_contacts VALUES ('.$contact['id'].', '.$groupId.', "'.$contact['phone_numbers'][0]['normalized'] . '")');
            }


        $duration = microtime_float() - $start;
        $queue->setStatusSuccess($jobId, $duration);
    }
    catch(Exception $e) {
        $duration = microtime_float() - $start;
        $queue->setStatusError($jobId, $e->getMessage(), $duration);
    }
}
