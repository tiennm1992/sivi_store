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

    

}
