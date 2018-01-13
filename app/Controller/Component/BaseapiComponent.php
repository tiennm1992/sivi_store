<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseapiComponent extends Component {

    function response($infor, $data = array(), $status = 1) {
        $data_api = array(
            'success' => $status,
            'infor' => $infor,
            'data' => $data,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
        die;
    }

    public function validate_data() {
        
    }

}
