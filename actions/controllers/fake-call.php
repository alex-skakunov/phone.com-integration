<?php

require_once 'vendor/autoload.php';

$client = new \GuzzleHttp\Client([
        'base_uri' => 'http://63.141.231.42/ani-route2/',
    ]);
$client->post('index.php?page=incoming', array(
    'body' => json_encode(array(
        'event_id' => rand(100, 1000),
        'payload'  => array(
            'call_id' => '1857ae9d-40ca-4f48-b81a-6fbb94633428',
            'from_did' => '+14356689666',
            'to_did'  =>  '+17258672634'
        )
    )),
    'headers' => array(
        'content-type' => 'application/json'
    )
));

exit('ok');
