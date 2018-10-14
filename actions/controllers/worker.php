<?php
$queue = new Queue;
$jobs = $queue->fetch(10);

$extensionApi = new Extension;

foreach ($jobs as $job) {
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
            $extensionApi->saveCallerToAddressBookAndGroup(
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
        $queue->setStatusError($jobId, $e->getMessage(), $duration);
    }
}
