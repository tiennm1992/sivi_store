<?php

App::uses('AppModel', 'Model');

/**
 * UserBuy Model
 *
 * @property Customer $Customer
 * @property Product $Product
 */
class UserBuy extends AppModel {

    public $useTable = "user_buys";

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'customer_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'product_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'price_origin' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'price_sale' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'revenue' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'date' => array(
            'datetime' => array(
                'rule' => array('datetime'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'customer_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'code',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function get_number_buy($code = 0, $month, $year) {
        $end_date = $year . '-' . $month . "-31 00:00:00";
        $start_date = $year . '-' . $month . "-1 00:00:00";
        $this->recursive = -1;
        $num_buy_data = $this->find('all', array(
            'conditions' => array(
                'UserBuy.code' => $code,
                'UserBuy.status' => 2,
                "UserBuy.date <= '{$end_date}'",
                "UserBuy.date > '{$start_date}'",
            )
        ));
        $num = 0;
        if (!empty($num_buy_data)) {
            foreach ($num_buy_data as $key => $value) {
                $num += $value['UserBuy']['number_product'];
            }
            return $num;
        }
        return 0;
    }

    public function get_number_buy_client($code = 0, $client_id) {
        $this->recursive = -1;
        $num_buy_data = $this->find('all', array(
            'conditions' => array(
                'UserBuy.code' => $code,
                'UserBuy.customer_id' => $client_id,
                'UserBuy.status' => 2,
            )
        ));
        $num = 0;
        if (!empty($num_buy_data)) {
            foreach ($num_buy_data as $key => $value) {
                $num += $value['UserBuy']['number_product'];
            }
            return $num;
        }
        return 0;
    }

    public function get_number_buy_sasi($code = 0) {
        $this->recursive = -1;
        $num_buy_data = $this->find('all', array(
            'conditions' => array(
                'UserBuy.code' => $code,
                'UserBuy.status' => 2,
            )
        ));
        $num = 0;
        if (!empty($num_buy_data)) {
            foreach ($num_buy_data as $key => $value) {
                $num += $value['UserBuy']['number_product'];
            }
            return $num;
        }
        return 0;
    }
    public function get_buy_client($customer_id) {
        $this->recursive = -1;
        $num_buy_data = $this->find('all', array(
            'conditions' => array(
                'UserBuy.customer_id' => $customer_id,
                'UserBuy.status' => 2,
            )
        ));
        $num = 0;
        if (!empty($num_buy_data)) {
            foreach ($num_buy_data as $key => $value) {
                $num += $value['UserBuy']['number_product'];
            }
            return $num;
        }
        return 0;
    }

}
