<?php

class FunctionsTest extends PHPUnit_Framework_TestCase {
    
    public function testQuery() {
        $stmt = query('SELECT 1');
        $this->assertInstanceOf('PDOStatement', $stmt);
    }
}