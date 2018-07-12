<?php

class Extension extends AbstractCall {

    public function get($extensionId) {
        $response = $this->get_client()->get('extensions/'.$extensionId);
        $this->print_response($response);
    }

    public function listContactGroups($extensionId) {
        $response = $this->get_client()->get('extensions/'.$extensionId.'/contact-groups');
        $this->print_response($response);
    }

    public function isCallerPresentInAddressBookAndGroup($extensionId, $contactGroupId, $callerPhoneNumber) {
        $response = $this->isCallerPresentInGroup($extensionId, $contactGroupId, $callerPhoneNumber);
        if (200 != $response->getStatusCode()) {
            throw new Exception($response->getReasonPhrase());
        }

        $data = json_decode($response->getBody()->getContents(), 1);
        if (empty($data)) {
            throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
        }

        return (int)$data['total'] > 0;
    }

    public function saveCallerToAddressBookAndGroup($extensionId, $contactGroupId, $callerPhoneNumber) {
        $response = $this->addContactOver7Minutes($extensionId, $contactGroupId, $callerPhoneNumber);
        if (!in_array($response->getStatusCode(), array(200, 201))) {
            throw new Exception($response->getReasonPhrase());
        }

        $data = json_decode($response->getBody()->getContents(), 1);
        if (empty($data)) {
            throw new Exception('Could not convert the JSON: ' . $response->getBody()->getContents());
        }
    }


    public function isCallerPresentInGroup($extensionId, $groupId, $callerPhoneNumber) {
        $response = $this->get_client()->get('extensions/'
            . $extensionId
            . '/contacts?limit=1&fields=brief&filters%5Bphone%5D='
            . $callerPhoneNumber
            . '&filters%5Bgroup_id%5D='
            . $groupId
        );
        return $response;
    }

    public function addContact($extensionId, $contactGroupId, $callerPhoneNumber) {
        return $this->get_client()->post(
            'extensions/'.$extensionId.'/contacts',
            array(
                'body' => json_encode(array(
                    "phone_numbers" => array(
                        array(
                            "type" => "business",
                            "number" => $callerPhoneNumber,
                            "normalized" => $callerPhoneNumber
                        )
                    ),

                    'group' => array(
                        'id'   => $contactGroupId,
                        'name' => "EXISTING ROUTE"
                    )

                ))
            )
        );
    }


    public function addContactOver7Minutes($extensionId, $contactGroupId, $callerPhoneNumber) {
        return $this->get_client()->post(
            'extensions/'.$extensionId.'/contacts',
            array(
                'body' => json_encode(array(
                    "phone_numbers" => array(
                        array(
                            "type" => "business",
                            "number" => $callerPhoneNumber,
                            "normalized" => $callerPhoneNumber
                        )
                    ),

                    'group' => array(
                        'id'   => $contactGroupId,
                        'name' => "OVER X MINUTES ROUTE"
                    )

                ))
            )
        );
    }

    public function deleteContact($extensionId=EXTENSION_TO, $contactId) {
        return $this->get_client()->delete('extensions/'.$extensionId.'/contacts/'.$contactId);
    }

    public function getMappingByShortExtension($short) {
        $stmt = query('SELECT * FROM mapping WHERE from_extension = ' . $short);
        $extensionMapping = $stmt->fetchAll();
        if (empty($extensionMapping)) {
            throw new Exception("Not found any mappings for extension: " . $extensionFrom);
        }
        return $extensionMapping;
    }

    public function fetchExtensionById($id) {
        $stmt = query('SELECT * FROM extensions WHERE extension_id = ' . $id);
        $extensionData = $stmt->fetch();
        if (empty($extensionData)) {
            throw new Exception("Extension not found in the database: " . $id);
        }
        return $extensionData;
    }

    public function fetchExtensionByShort($short) {
        $stmt = query('SELECT * FROM extensions WHERE short_extension = ' . $short);
        $extensionData = $stmt->fetch();
        if (empty($extensionData)) {
            throw new Exception("Short extension not found in the database: " . $short);
        }
        return $extensionData;
    }

    public function reloadAll() {
        $response = $this->get_client()->get('extensions?limit=500');

        if (200 != $response->getStatusCode()) {
            throw new Exception($response->getReasonPhrase());
        }

        $data = json_decode($response->getBody()->getContents(), 1);
        if (empty($data)) {
            throw new Exception('Could not convert the JSON: ' . substr($response->getBody()->getContents(), 0, 200));
        }

        query('TRUNCATE TABLE extensions');
        foreach ($data['items'] as $extensionRecord) {
            query('INSERT INTO extensions VALUES (:id, :short_id, :name)', array(
                ':id' => $extensionRecord['id'],
                ':short_id' => $extensionRecord['extension'],
                ':name' => $extensionRecord['name']
            ));
        };

        query('UPDATE `extensions` SET `existing_route_group_id`=388712 WHERE `extension_id`=1854653');
        query('UPDATE `extensions` SET `existing_route_group_id`=388844 WHERE `extension_id`=1855889');

        query('UPDATE `extensions` SET `existing_route_group_id`=388917 WHERE `extension_id`=1843686');
        query('UPDATE `extensions` SET `existing_route_group_id`=388918 WHERE `extension_id`=1844661');
        query('UPDATE `extensions` SET `existing_route_group_id`=388713 WHERE `extension_id`=1854656');

        query('UPDATE `extensions` SET `existing_route_group_id`=388714 WHERE `extension_id`=1854658');
        query('UPDATE `extensions` SET `existing_route_group_id`=388848 WHERE `extension_id`=1855896');
        query('UPDATE `extensions` SET `existing_route_group_id`=388852 WHERE `extension_id`=1855898');

        query('UPDATE `extensions` SET `existing_route_group_id`=388715 WHERE `extension_id`=1854661');
        query('UPDATE `extensions` SET `existing_route_group_id`=388856 WHERE `extension_id`=1855900');
        query('UPDATE `extensions` SET `existing_route_group_id`=388860 WHERE `extension_id`=1855902');

        query('UPDATE `extensions` SET `existing_route_group_id`=388716 WHERE `extension_id`=1854663');
        query('UPDATE `extensions` SET `existing_route_group_id`=388922 WHERE `extension_id`=1843687');
        query('UPDATE `extensions` SET `existing_route_group_id`=388864 WHERE `extension_id`=1855903');

        query('UPDATE `extensions` SET `existing_route_group_id`=388717 WHERE `extension_id`=1854665');
        query('UPDATE `extensions` SET `existing_route_group_id`=388923 WHERE `extension_id`=1843678');
        query('UPDATE `extensions` SET `existing_route_group_id`=388924 WHERE `extension_id`=1843680');
        query('UPDATE `extensions` SET `existing_route_group_id`=388925 WHERE `extension_id`=1843690');
        

        query('UPDATE `extensions` SET `over_x_minutes_group_id`=389434 WHERE `extension_id`=1843678;');
        query('UPDATE `extensions` SET `over_x_minutes_group_id`=389427 WHERE `extension_id`=1854207;');
    }

    protected function _getMappings($tableName) {
        $mappings = array();
        $stmt = query('SELECT * FROM ' . $tableName);
        $mappingData = $stmt->fetchAll();
        foreach ($mappingData as $mappingRecord) {
            $from = $mappingRecord['from_extension'];
            $to = $mappingRecord['to_extension'];
            $mappings[$from][] = $to;
        }
        return $mappings;
    }

    public function getMappings() {
        return $this->_getMappings('mapping');
    }

    public function getDurationMappings() {
        return $this->_getMappings('mapping_duration');
    }


}