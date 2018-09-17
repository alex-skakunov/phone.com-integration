<?php
include_once 'classes/ContactsToKillQueue.php';

$queue = new ContactsToKillQueue;
$jobs = $queue->fetch(3);
$extensionApi = new Extension;

foreach ($jobs as $job) {
    $start = microtime_float();
    new dBug($job);

    $jobId = $contactId = $job['id'];
    $extensionId = $job['extension_id'];
    $queue->setStatusProcessing($jobId);

    try {
        $response = $extensionApi->deleteContact($extensionId, $contactId);

        $data = json_decode($response->getBody()->getContents(), 1);
        if ('TRUE' != $data['success']) {
            throw new Exception('Something went wrong');
        }
        $duration = microtime_float() - $start;
        $queue->setStatusSuccess($jobId, $duration);
    }
    catch(Exception $e) {
        $duration = microtime_float() - $start;
        $queue->setStatusError($jobId, $e->getMessage(), $duration);
    }
}