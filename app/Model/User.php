<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * User Model
 *
 */
class User extends AppModel {

    public $useTable = 'users';


    public function beforeFilter() {
        $this->Auth->allow('index');
    }

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher(array('hashType' => 'md5'));
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }

    public function encryptPassword($password) {
        $passwordHasher = new SimplePasswordHasher(array('hashType' => 'md5'));
        return $passwordHasher->hash($password);
    }


    public function checkExitsCode($code) {
        $data = $this->find('all', array('conditions' => array('User.code' => $code)));
        if ($data) {
            return TRUE;
        }
        return FALSE;
    }

    public function checkExitsUser($username) {
        $data = $this->find('all', array('conditions' => array('User.username' => $username)));
        if ($data) {
            return TRUE;
        }
        return FALSE;
    }

}
