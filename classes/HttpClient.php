<?php

class HttpClient {
	
    protected static $_instance;

	public static function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new \GuzzleHttp\Client(
                array(
                    'base_uri' => 'https://api.phone.com/v4/accounts/'.ACCOUNT_ID.'/',
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