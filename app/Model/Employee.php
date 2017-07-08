<?php

App::uses('AppModel', 'Model');

/**
 * Employee Model
 *
 */
class Employee extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'phone' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'code' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'full_name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );

    public function checkExitsCode($code) {
        $data = $this->find('all', array('conditions' => array('Employee.code' => $code)));
        if ($data) {
            return TRUE;
        }
        return FALSE;
    }
}
