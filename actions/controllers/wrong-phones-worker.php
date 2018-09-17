<?php
include_once 'classes/WrongPhonesQueue.php';

$queue = new WrongPhonesQueue;
$jobs = $queue->fetch(25, 'RAND()');

$extensionApi = new Extension;

foreach ($jobs as $job) {
    $start = microtime_float();
    new dBug($job);

    $jobId =  $job['id'];
    $callerPhoneNumber =  $job['phone_number'];
    $shortExtension =  $job['short_extension'];
    $queue->setStatusProcessing($jobId);

    try {
            new dBug(array('extension' => $shortExtension));
            $extension = $extensionApi->fetchExtensionByShort($shortExtension);
            $extensionId = $extension['extension_id'];
            $contactId = $job['contact_id'];
            $groupId = $job['group_id'];
/*
            $response = $extensionApi->getCallerInExtension($extensionId, $callerPhoneNumber);
            $data = json_decode($response->getBody()->getContents(), 1);
            new dBug($data);
            if (empty($data['items'])) {
                new dBug('not present');
                $duration = microtime_float() - $start;
                $queue->setStatusSuccess($jobId, $duration);
                continue;
            }

            $contactId = $data['items'][0]['id'];
            $group = $data['items'][0]['group'];
            $groupId = $group['id'];
            query('UPDATE `wrong_phones` SET 
                    `group_id` = '.(('OVER X MINUTES ROUTE' == trim($group['name'])) ? $groupId : 'NULL') .',
                    `contact_id` = '.$contactId.'
                   WHERE id = ' . $jobId);
*/

/*
            if ('OVER X MINUTES ROUTE' == trim($group['name'])) {
                $extensionApi->deleteGroup($extensionId, $group['id']);
                new dBug('Deleted the group');
            }
*/
//            $extensionApi->deleteContact($extensionId, $contactId);

            $extensionApi->deleteGroup($extensionId, $groupId);
            new dBug('deleted the group');

        $duration = microtime_float() - $start;
        $queue->setStatusSuccess($jobId, $duration);
    }
    catch(Exception $e) {
        $duration = microtime_float() - $start;
        $queue->setStatusError($jobId, $e->getMessage(), $duration);
    }
}
