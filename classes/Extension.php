<?php

class Extension extends AbstractCall {

    public function get($extensionId) {
        $response = $this->get_client()->get('extensions/'.$extensionId);
        $this->print_response($response);
    }

    function listContactGroups($extensionId) {
        $response = $this->get_client()->get('extensions/'.$extensionId.'/contact-groups');
        $this->print_response($response);
    }

    function isCallerPresent($extensionId, $callerPhoneNumber) {
        $response = $this->get_client()->get('extensions/'.$extensionId.'/contacts?limit=1&fields=brief&filters%5Bphone%5D='
            .$callerPhoneNumber);
        return $response;
    }

    function isCallerPresentInGroup($extensionId, $groupId, $callerPhoneNumber) {
        $response = $this->get_client()->get('extensions/'
            . $extensionId
            . '/contacts?limit=1&fields=brief&filters%5Bphone%5D='
            . $callerPhoneNumber
            . '&filters%5Bgroup_id%5D='
            . $groupId
        );
        return $response;
    }

    function addContact($extensionId, $contactGroupId, $callerPhoneNumber) {
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


    function addContactOver7Minutes($extensionId, $contactGroupId, $callerPhoneNumber) {
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

    function deleteContact($extensionId=EXTENSION_TO, $contactId) {
        return $this->get_client()->delete('extensions/'.$extensionId.'/contacts/'.$contactId);
    }
}