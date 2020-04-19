<?php

/**
 * this is a http client for a new API version that phone.com introduced for us in March 2020
 */

class HttpClient2 {

    protected static $_instance;

    public static function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new \GuzzleHttp\Client(
                array(
                    'base_uri' => 'https://tools.phone.com/scs/pfm-media/',
                    'headers' => array(
                        'Authorization' => "Bearer " . ACCESS_TOKEN,
                        'cache-control' => 'no-cache',
                        'content-type' => 'application/json'
                    )
                )
            );
        }
        return self::$_instance;
    }

}