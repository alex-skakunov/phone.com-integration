<?php
include_once 'classes/GroupsToKillQueue.php';

$queue = new GroupsToKillQueue;
$jobs = $queue->fetch(3);
$extensionApi = new Extension;

foreach ($jobs as $job) {
    $start = microtime_float();
    new dBug($job);

    $jobId = $groupId = $job['id'];
    $extensionId = $job['extension_id'];
    $queue->setStatusProcessing($jobId);

    try {
        $response = $extensionApi->deleteGroup($extensionId, $groupId);

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