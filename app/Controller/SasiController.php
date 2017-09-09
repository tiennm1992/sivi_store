<?php
App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class SasiController extends AppController {

    public $components = array('Paginator');
    public $uses = array('Customer','UserPosition', 'User', 'UserBuy', 'UserPosition');
    public $user_info;
    public $user_code;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->user_info = $this->Auth->user();
        $this->user_code = $this->user_info['code'];
    }

    public function isAuthorized($user) {
        if ($user['role'] != 'employee') {
            throw new NotFoundException('Không có quyền truy cập');
        }
        return parent::isAuthorized($user);
    }

    public function infor() {
        $user = $this->Auth->user();
    }

    public function summary() {
        $date = date("Y-m");
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];
        $revenue_sasi = $this->UserPosition->find('first', array(
            'conditions' => array(
                'UserPosition.code' => $this->user_code,
                'UserPosition.month' => $month,
                'UserPosition.year' => $year,
            )
        ));
        if (!empty($revenue_sasi)) {
            $revenue_sasi = $revenue_sasi['UserPosition'];
        } else {
            $revenue_sasi = array(
                'sasi_position' => 0,
                'sasi_sub_position' => 0,
                'revenue' => 0,
                'profit' => 0,
                'point_dr' => 0,
                'point_dc' => 0,
                'point_d' => 0,
                'month' => $month,
                'year' => $year
            );
        }
        $number_buy = $this->UserBuy->get_number_buy($this->user_code);
        $sasi_list = $this->UserPosition->get_sub_position_list($this->user_code);
        $number_customer= $this->Customer->get_num_customer($this->user_code);
        $this->set('sasi', $revenue_sasi);
        $this->set('number_buy', $number_buy);
        $this->set('number_customer', $number_customer);
        $this->set('sasi_list', $sasi_list);
        $this->set('user_name', $this->user_info['name']);
    }

    public function order_list() {
        try {
            $conditions = array(
                'UserBuy.status' => 2,
                'UserBuy.code' => $this->user_info['code'],
            );
            $this->UserBuy->recursive = -1;
            $this->Paginator->settings = array(
                'fields' => array('Customer.*', 'Product.*', 'UserBuy.*', 'User.*',),
                'conditions' => $conditions,
                'limit' => 10,
                'order' => array(
                    'UserBuy.id' => 'DESC'
                ),
                'joins' => array(
                    array(
                        'table' => 'customers',
                        'alias' => 'Customer',
                        'type' => 'inner',
                        'conditions' => array(
                            'UserBuy.customer_id=Customer.id'
                        )
                    ),
                    array(
                        'table' => 'products',
                        'alias' => 'Product',
                        'type' => 'inner',
                        'conditions' => array(
                            'UserBuy.product_id=Product.id'
                        )
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'inner',
                        'conditions' => array(
                            'User.code=UserBuy.code'
                        )
                    )
                )
            );
            $this->set('userBuys', $this->Paginator->paginate('UserBuy'));
            $this->set('title_for_layout', 'Danh đơn hàng thành công');
        } catch (Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

    public function sasi_list() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['User.username LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $conditions['User.role'] = 'employee';
        $conditions['User.sale_id_protected'] = $this->user_code;
        $this->Paginator->settings = array(
            'conditions' =>
            $conditions
        );
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate('User'));
        $this->set('title_for_layout', 'Danh sách Sasi');
    }

    public function policy() {
        $this->render(false);
    }

    public function convert_list() {
//        $this->layout = false;
        $this->render(false);
    }

    public function test() {
        $a = $this->UserPosition->get_revenue(20170720, 'c1');
//        $a= $this->UserPosition->get_profit(20170720);
        pr($a);
        die;
    }

}
