<?php

class Listener extends AbstractCall {

    public function listAll() {
        $response = $this->get_client()->get('listeners');
        $this->print_response($response);
    }

    public function addListener($extensionId=EXTENSION_FROM) {
        $response = $this->get_client()->post(
            'extensions/'.$extensionId.'/listeners',
            array(
                'body' => '{
                "type" : "callback", 
                "event_type" : "call.log", 
                "callbacks" : [{ 
                    "role" : "main", 
                    "url": "http://63.141.231.42/ani-route/incoming.php", 
                    "verb": "POST" 
                    }]
                }'
            )
        );
        $this->print_response($response);
    }

    function deleteListener($id) {
        $response = $this->get_client()->delete('listeners/' . $id);
        $this->print_response($response);
    }
}