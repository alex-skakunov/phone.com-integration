<?php

class ContactsToKillQueue {

    public function fetch($limit=10, $direction='id ASC') {
        $stmt = query('SELECT * FROM _contacts_to_kill WHERE status="queued" ORDER BY '. $direction.' LIMIT ' . (int)$limit);
        return $stmt->fetchAll();
    }

    public function setStatusProcessing($id) {
        query('UPDATE _contacts_to_kill SET status="processing", error_message=DEFAULT, processing_time=DEFAULT WHERE id=' . $id);
    }

    public function setStatusError($id, $errorMessage, $processingTime=null) {
        query(
            'UPDATE _contacts_to_kill SET
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
            'UPDATE _contacts_to_kill SET
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
        query('DELETE FROM _contacts_to_kill WHERE id=' . $id);
    }
}