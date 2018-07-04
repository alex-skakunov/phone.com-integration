<?php

class ExtensionTest extends PHPUnit_Framework_TestCase {

    public function testContactsCheck() {
        $extensionApi = new Extension;
        
        $response = $extensionApi->isCallerPresentInGroup('1843686', '388917', '+16159755740');
        $this->assertInstanceOf('GuzzleHttp\Psr7\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody()->getContents(), 1);
        $this->assertTrue(!empty($data));
        $this->assertEquals(1, (int)$data['total']);
    }

    public function testFetchExtensionById() {
        $extensionApi = new Extension;        
        $extension = $extensionApi->fetchExtensionById(1854651);
        $this->assertInternalType('array', $extension);
        $this->assertEquals('9064', $extension['short_extension']);
    }

    public function testFetchExtensionByShort() {
        $extensionApi = new Extension;        
        $extension = $extensionApi->fetchExtensionByShort(9064);
        $this->assertInternalType('array', $extension);
        $this->assertEquals(1854651, $extension['extension_id']);
    }

    public function testGetMappingForExtension() {
        $extensionApi = new Extension;        
        $mapping = $extensionApi->getMappingByShortExtension(9064);
        $this->assertInternalType('array', $mapping);
        $this->assertCount(2, $mapping);
        $this->assertEquals('9065', current($mapping)['to_extension']);
    }

    public function testGetMappings() {
        $extensionApi = new Extension;        
        $mapping = $extensionApi->getMappings();
        $this->assertInternalType('array', $mapping);
        $this->assertGreaterThan(0, sizeof($mapping));

        //length of any extension is 4, so let's check the key and its first value
        $this->assertEquals(4, strlen(key($mapping)));
        $this->assertEquals(4, strlen(current(current($mapping))));
    }

    public function testGetDurationMappings() {
        $extensionApi = new Extension;        
        $mapping = $extensionApi->getDurationMappings();
        $this->assertInternalType('array', $mapping);
        $this->assertGreaterThan(0, sizeof($mapping));

        //length of any extension is 4, so let's check the key and its first value
        $this->assertEquals(4, strlen(key($mapping)));
        $this->assertEquals(4, strlen(current(current($mapping))));
    }

}