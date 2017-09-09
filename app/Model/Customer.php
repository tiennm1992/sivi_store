<?php

App::uses('AppModel', 'Model');

/**
 * Customer Model
 *
 */
class Customer extends AppModel {

    function checkUser($username) {
        $data = $this->find('all', array('conditions' => array('username' => $username)));
        if ($data) {
            return 0;
        } else {
            return 1;
        }
    }

    public function get_num_customer($user_code) {
        $data = $this->find('count', array(
            'conditions' => array(
                'employee_code' => $user_code
            )
        ));
        return $data;
    }

}
