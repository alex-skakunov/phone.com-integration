<?php

class CallLogTest extends PHPUnit_Framework_TestCase {

    public function testGet() {
        $api = new CallLog;
        $response = $api->get('a3ae24e5-0a04-41fc-85fc-b88312076746');
        $this->assertInstanceOf('GuzzleHttp\Psr7\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody()->getContents(), 1);
        $this->assertTrue(!empty($data));
        $this->assertEquals('+18632486963', $data['called_number']);
    }
}

