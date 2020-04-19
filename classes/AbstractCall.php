<?php
include_once 'HttpClient.php';
include_once 'HttpClient2.php';

class AbstractCall {

    protected function get_client() {
        return HttpClient::getInstance();
    }

    protected function get_client2() {
        return HttpClient2::getInstance();
    }

    protected function print_response($response) {
        new dBug(
            array(
                'code'      => $response->getStatusCode(),
                'reason'    => $response->getReasonPhrase(),
                //'headers'   => (array)$response->getHeaders(),
                'body'      => json_decode($response->getBody()->getContents(), 1)
            )
        );
    }
}