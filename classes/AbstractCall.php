<?php
include_once 'HttpClient.php';

class AbstractCall {

    protected function get_client() {
        return HttpClient::getInstance();
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