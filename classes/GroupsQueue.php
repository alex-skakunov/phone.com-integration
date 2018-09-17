<?php

class GroupsQueue {

    public function fetch($limit=10, $direction='id ASC') {
        $stmt = query('SELECT * FROM _groups WHERE status="queued" ORDER BY '. $direction.' LIMIT ' . (int)$limit);
        return $stmt->fetchAll();
    }

    public function setStatusProcessing($id) {
        query('UPDATE _groups SET status="processing", error_message=DEFAULT, processing_time=DEFAULT WHERE id=' . $id);
    }

    public function setStatusError($id, $errorMessage, $processingTime=null) {
        query(
            'UPDATE _groups SET
                status="queued",
                error_message=:error_message,
                processing_time=:processing_time
             WHERE id=' . $id,
            array(
                ':error_message'   => $errorMessage,
                ':processing_time' => $processingTime
            )
        );
    }

    public function setStatusSuccess($id, $processingTime=null) {
        query(
            'UPDATE _groups SET
                status="success",
                error_message=DEFAULT,
                processing_time=:processing_time
             WHERE id=' . $id,
            array(
                ':processing_time' => $processingTime
            )
        );
    }

    public function removeRecord($id) {
        query('DELETE FROM _groups WHERE id=' . $id);
    }
}