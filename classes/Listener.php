<?php

class Listener extends AbstractCall {

    public function listAll() {
        $response = $this->get_client()->get('listeners');
        $this->print_response($response);
    }

    public function addListener() {
        $response = $this->get_client()->post(
            'listeners',
            array(
                'body' => '{
                "type" : "callback", 
                "event_type" : "call.update", 
                "callbacks" : [{ 
                    "role" : "main", 
                    "url": "http://63.141.231.42/ani-route2/index.php?page=incoming", 
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