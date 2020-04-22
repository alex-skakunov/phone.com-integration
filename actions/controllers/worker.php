<?php
$queue = new Queue;
$jobs = $queue->fetch(18);

$extensionApi = new Extension;

foreach ($jobs as $job) {
new dBug($job);
    $start = microtime_float();

    $jobId = $job['id'];
    $queue->setStatusProcessing($jobId);

    $shortExtension = $job['extension_to'];

    $extension = $extensionApi->fetchExtensionByShort($shortExtension);
    $extensionId = $extension['extension_id'];

    $contactGroupId = (EXISTING_ROUTE == $job['contact_group'])
        ? $extension['existing_route_group_id']
        : $extension['over_x_minutes_group_id'];

    $contactGroupName = (EXISTING_ROUTE == $job['contact_group'])
        ? 'EXISTING ROUTE'
        : 'OVER X MINUTES ROUTE';

    $callerPhoneNumber = $job['phone_number'];

    try {
        $isPresent = $extensionApi->isCallerPresentInAddressBookAndGroup($extensionId, $contactGroupId, $callerPhoneNumber);
        if (!$isPresent) {
            new dBug('not present');
            $responseData = $extensionApi->saveCallerToAddressBookAndGroup(
                $extensionId,
                $contactGroupId,
                $contactGroupName,
                $callerPhoneNumber
            );
        }
        else {
            new dBug('was already present');
        }

        $duration = microtime_float() - $start;
        $queue->setStatusSuccess($jobId, $duration);
    }
    catch(Exception $e) {
        $duration = microtime_float() - $start;

        $reQueue = false;

        $is500 = strpos($e->getMessage(), '500 Internal Server Error') != false;
        $is504 = strpos($e->getMessage(), '504 ') != false;
        $is422 = strpos($e->getMessage(), '422 Unprocessable Entity') != false;
        $is402 = strpos($e->getMessage(), '402 ') != false;

        if ($is500 || $is504) {
            $reQueue = true;
        }

        $isOtherError = !$is500 && !$is504 && !$is422 && !$is402;
        if ($isOtherError) {
            sendFailEmail('A new error has occured: ' . $e->getMessage());
        }

        $queue->setStatusError($jobId, $e->getMessage(), $duration, $reQueue);
    }
}
