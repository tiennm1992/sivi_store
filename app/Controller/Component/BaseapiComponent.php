<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseapiComponent extends Component {

    public $user_id;

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
        $data = $this->request->data;
        if (!empty($data['token']) && !empty($data['user_id'])) {
            $data = $this->Customer->find('all', array('conditions' => array('token' => $data['token'])));
            if ($data) {
                $this->user_id = $data['user_id'];
            } else {
                $this->response('Nguoi dung chua dang nhap', array(), API_ERROR);
            }
        } else {
            $this->response('Missing param: user_id, Token', array(), API_ERROR);
        }
    }

    public function add_check_like($data) {
        foreach ($data as $key1 => $value1) {
            if (!empty($data[$key1]['Social']['id'])) {
                $data[$key1]['Product']['is_like'] = $data[$key1]['Social']['like'];
            } else {
                $data[$key1]['Product']['is_like'] = 0;
            }
        }
        return $data;
    }

}
