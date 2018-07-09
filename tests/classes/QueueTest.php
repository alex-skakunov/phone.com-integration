<?php

class QueueTest extends PHPUnit_Framework_TestCase {

    public function testAdd() {
        $queue = new Queue;
        $id = $queue->add('444', '666', EXISTING_ROUTE, '+1234567890', 'call.update', array('extension' => 1234));
        $this->assertGreaterThan(0, $id);
        $queue->setStatusProcessing($id);
        $queue->setStatusError($id, 'some error message', 2.345);
        $queue->setStatusSuccess($id, 2.345);
        $queue->removeRecord($id);
    }
}

