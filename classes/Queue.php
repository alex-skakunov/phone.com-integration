<?php

class Queue {

    public function add($shortExtension, $contactGroupName, $phoneNumber) {
        global $db;
        $stmt = query(
            'INSERT IGNORE INTO queue (`extension_to`, `contact_group`, `phone_number`, `created_at`)
             VALUES (:short_extension, :contact_group, :phone_number, NOW())',
            array(
                ':short_extension' => $shortExtension,
                ':contact_group' => $contactGroupName,
                ':phone_number' => $phoneNumber
            )
        );
        return $db->lastInsertId();
    }

    public function fetch($limit=10) {
        $stmt = query('SELECT * FROM queue WHERE status="queued" LIMIT ' . (int)$limit);
        return $stmt->fetchAll();
    }

    public function setStatusProcessing($id) {
        query('UPDATE queue SET status="processing", error_message=DEFAULT, processing_time=DEFAULT WHERE id=' . $id);
    }

    public function setStatusError($id, $errorMessage, $processingTime=null) {
        query(
            'UPDATE queue SET
                status="error",
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
            'UPDATE queue SET
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
        query('DELETE FROM queue WHERE id=' . $id);
    }
}