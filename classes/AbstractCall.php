<?php

class AbstractCall {

    protected function get_client() {
        return new \GuzzleHttp\Client([
            'base_uri' => 'https://api.phone.com/v4/accounts/'.ACCOUNT_ID.'/',
            'headers' => array(
                'Authorization' => "Bearer " . ACCESS_TOKEN,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json'
            )
        ]);
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