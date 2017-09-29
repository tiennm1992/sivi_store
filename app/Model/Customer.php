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

    public function get_customer($user_code, $last_id = 0, $limit = 10) {
        $conditions = array();
        $conditions['employee_code'] = $user_code;
        if (!empty($last_id)) {
            $conditions['id <'] = $last_id;
        }
        $data = $this->find('all', array(
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => array("Customer.id DESC")
        ));
        return $data;
    }

}
