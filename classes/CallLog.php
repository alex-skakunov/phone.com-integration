<?php

class CallLog extends AbstractCall {

    public function get($call_id) {
        $response = $this->get_client()->get('call-logs/' . $call_id);
        return $response;
    }

    function getCallsFromLogs($shortExtension, $lookback='1 hour ago', $limit=500) {
        $extensionApi = new Extension;
        $extension = $extensionApi->fetchExtensionByShort($shortExtension);
        $response = $this->getForExtension($extension['extension_id'], $lookback, $limit);

        if (200 != $response->getStatusCode()) {
            throw new Exception($response->getReasonPhrase());
        }

        $data = json_decode($response->getBody()->getContents(), 1);
        if (empty($data)) {
            throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
        }

        return $data['items'];
    }

    public function getForExtension($extensionId, $lookback, $limit=500) {
        $timestamp = strtotime($lookback);
        $uri = 'call-logs/?fields=brief&filters[extension]='
            . $extensionId
            . '&filters[created_at]=gt:'
            . $timestamp
            . '&limit='
            . $limit;
        new dBug($uri);
        $response = $this->get_client()->get($uri);
        return $response;
    }

}