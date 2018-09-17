<?php

class WrongPhonesQueue {

    public function fetch($limit=10, $direction='id ASC') {
        $stmt = query('SELECT * FROM wrong_phones WHERE status="queued" ORDER BY '. $direction.' LIMIT ' . (int)$limit);
        return $stmt->fetchAll();
    }

    public function setStatusProcessing($id) {
        query('UPDATE wrong_phones SET status="processing", error_message=DEFAULT, processing_time=DEFAULT WHERE id=' . $id);
    }

    public function setStatusError($id, $errorMessage, $processingTime=null) {
        query(
            'UPDATE wrong_phones SET
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
            'UPDATE wrong_phones SET
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
        query('DELETE FROM wrong_phones WHERE id=' . $id);
    }
}