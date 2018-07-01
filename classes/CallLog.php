<?php

class CallLog extends AbstractCall {

    public function list_call_logs() {
        $response = $this->get_client()->get('call-logs?limit=1&filters%5Bcalled_number%5D=%2B17258672634');
        $this->print_response($response);
    }

    public function get($call_id) {
        $response = $this->get_client()->get('call-logs/' . $call_id);
        return $response;
    }

    public function getForExtension($ext, $limit) {
        $timestamp = strtotime('3 minutes ago');
        $uri = 'call-logs/?filters[extension]='
            . $ext
            . '&filters[created_at]=gt:'
            . $timestamp
            . '&limit='
            . $limit;
        new dBug($uri);
        $response = $this->get_client()->get($uri);
        return $response;
    }

    public function getForExtensionLast2Days($ext, $limit) {
        $timestamp = strtotime('5 minutes ago');
        $uri = 'call-logs/?filters[extension]='
            . $ext
            . '&filters[created_at]=gt:'
            . $timestamp
            . '&limit='
            . $limit;
        new dBug($uri);
        $response = $this->get_client()->get($uri);
        return $response;
    }
}