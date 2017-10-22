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
    public $uses = array('ComparePosition', 'Customer', 'UserPosition', 'UserLevel', 'User', 'UserBuy', 'UserPosition', 'Category', 'Product', 'ExchangePositions');
    public $user_info;
    public $user_code;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->user_info = $this->Auth->user();
        $this->user_code = $this->user_info['code'];
    }

    public function isAuthorized($user) {
        $action = $this->request->params['action'];
        if ($user['role'] != 'employee' && $action != 'summary_admin') {
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
        $query = $this->request->query;
        if (isset($query['date']) && !empty($query['date'])) {
            $date = explode('-', $query['date']);
            $month = $date[1];
            $year = $date[0];
        }
        $revenue_sasi = $this->UserPosition->find('first', array(
            'conditions' => array(
                'UserPosition.code' => $this->user_code,
                'UserPosition.month' => $month,
                'UserPosition.year' => $year,
            )
        ));
        $best_sasi = $this->UserPosition->find('first', array(
            'conditions' => array(
                'UserPosition.code' => $this->user_code,
                'UserPosition.year' => $year,
            ),
            'order' => array('UserPosition.sasi_position DESC', 'UserPosition.sasi_sub_position DESC')
        ));
        $current_position = 'sasi';
        $best_position = 'sasi';
        if (!empty($best_sasi)) {
            $best_position = $this->UserLevel->convert_position($best_sasi['UserPosition']['level']);
        }
        if (!empty($revenue_sasi)) {
            $revenue_sasi = $revenue_sasi['UserPosition'];
            $current_position = $this->UserLevel->convert_position($revenue_sasi['level']);
        } else {
            $revenue_sasi = array(
                'sasi_position' => 0,
                'sasi_sub_position' => 0,
                'revenue' => 0,
                'profit' => 0,
                'profit_cc' => 0,
                'point_dr' => 0,
                'point_dc' => 0,
                'point_d' => 0,
                'month' => $month,
                'year' => $year
            );
        }
        $revenue_sasi['point_dc'] = $this->UserLevel->get_point_dc($this->user_code);
        if ($current_position == 'sasi' || $current_position == 'sasim') {
            $revenue_sasi['point_dc'] = 0;
            $revenue_sasi['point_dr'] = 0;
            $revenue_sasi['point_d'] = 0;
        }
        $number_buy = $this->UserBuy->get_number_buy($this->user_code, $month, $year);
        $sasi_list = $this->UserLevel->get_sub_position_list($this->user_code, $month, $year);

        $number_customer = $this->Customer->get_num_customer($this->user_code, $month, $year);
        $new_num_customer = $this->Customer->get_new_num_customer($this->user_code, $month, $year);
        $this->set('sasi', $revenue_sasi);
        $this->set('current_position', $current_position);
        $this->set('best_position', $best_position);
        $this->set('number_buy', $number_buy);
        $this->set('number_customer', $number_customer);
        $this->set('new_num_customer', $new_num_customer);
        $this->set('sasi_list', $sasi_list);
        $this->set('user_name', $this->user_info['name']);
        $this->set('date1', $year . '-' . $month);
        $this->set('title_for_layout', 'Thống kê sasi tháng ' . $month . ' năm ' . $year);
    }

    public function summary_admin($code = 0) {
        $date = date("Y-m");
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];
        $query = $this->request->query;
        if (isset($query['date']) && !empty($query['date'])) {
            $date = explode('-', $query['date']);
            $month = $date[1];
            $year = $date[0];
        }
        $revenue_sasi = $this->UserPosition->find('first', array(
            'conditions' => array(
                'UserPosition.code' => $code,
                'UserPosition.month' => $month,
                'UserPosition.year' => $year,
            )
        ));
        $best_sasi = $this->UserPosition->find('first', array(
            'conditions' => array(
                'UserPosition.code' => $code,
                'UserPosition.year' => $year,
            ),
            'order' => array('UserPosition.sasi_position DESC', 'UserPosition.sasi_sub_position DESC')
        ));
        $current_position = 'sasi';
        $best_position = 'sasi';
        if (!empty($best_sasi)) {
            $best_position = $this->UserLevel->convert_position($best_sasi['UserPosition']['level']);
        }
        if (!empty($revenue_sasi)) {
            $revenue_sasi = $revenue_sasi['UserPosition'];
            $current_position = $this->UserLevel->convert_position($revenue_sasi['level']);
        } else {
            $revenue_sasi = array(
                'sasi_position' => 0,
                'sasi_sub_position' => 0,
                'revenue' => 0,
                'profit' => 0,
                'profit_cc' => 0,
                'point_dr' => 0,
                'point_dc' => 0,
                'point_d' => 0,
                'month' => $month,
                'year' => $year
            );
        }
        $revenue_sasi['point_dc'] = $this->UserLevel->get_point_dc($code);
        if ($current_position == 'sasi' || $current_position == 'sasim') {
            $revenue_sasi['point_dc'] = 0;
            $revenue_sasi['point_dr'] = 0;
            $revenue_sasi['point_d'] = 0;
        }
        $number_buy = $this->UserBuy->get_number_buy($code, $month, $year);
        $sasi_list = $this->UserLevel->get_sub_position_list($code, $month, $year);

        $number_customer = $this->Customer->get_num_customer($code, $month, $year);
        $new_num_customer = $this->Customer->get_new_num_customer($code, $month, $year);
        $user_data = $this->User->find('first', array(
            'conditions' => array(
                'code' => $code
            )
        ));
        if (!empty($user_data['User'])) {
            $user_name = $user_data['User']['name'];
        } else {
            $user_name = '';
        }
        $this->set('sasi', $revenue_sasi);
        $this->set('current_position', $current_position);
        $this->set('best_position', $best_position);
        $this->set('number_buy', $number_buy);
        $this->set('number_customer', $number_customer);
        $this->set('new_num_customer', $new_num_customer);
        $this->set('sasi_list', $sasi_list);
        $this->set('user_name', $user_name);
        $this->set('date1', $year . '-' . $month);
        $this->set('title_for_layout', 'Thống kê sasi tháng ' . $month . ' năm ' . $year);
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

    public function compare() {
        $this->ComparePosition->recursive = 0;
        $this->set('ComparePosition', $this->Paginator->paginate('ComparePosition'));
        $this->set('title_for_layout', 'Bảng đối chiếu');
    }

    public function convert_list() {
        $this->ExchangePositions->recursive = 0;
        $this->set('ExchangePositions', $this->Paginator->paginate('ExchangePositions'));
        $this->set('title_for_layout', 'Bảng quy đổi');
    }

    public function test() {
        $a = $this->UserPosition->get_revenue(20170720, 'c1');
//        $a= $this->UserPosition->get_profit(20170720);
        pr($a);
        die;
    }

    public function user_infor() {
        $infor = $this->user_info;
        $this->User->id = $infor['id'];
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->findById($infor['id']);
        $this->set('user', $user['User']);
        $this->set('user_infor', $user['User']);
    }

    public function products_list() {
        //get category
        $this->Category->unbindModel(array('hasMany' => array('Product', 'Subcategory')));
        $list_category = $this->Category->find('all', array('fields' => array('Category.id', 'Category.name')));
        //get search
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['Product.name LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $category = '';
        if (isset($query['category']) && !empty($query['category'])) {
            $conditions['Product.category_id'] = $query['category'];
            $category = $query['category'];
        }
        $start_time = isset($query['startdate']) ? $query['startdate'] : null;
        $end_time = isset($query['enddate']) ? $query['enddate'] : null;
        if ($start_time && $end_time) {
            $conditions[] = "Product.date_create >= date('{$start_time}') AND Product.date_create<= date('{$end_time}')";
        } else if ($end_time) {
            $conditions[] = "Product.date_create <= date('{$end_time}')";
        } else if ($start_time) {
            $conditions[] = "Product.date_create >= date('{$start_time}')";
        }
        $this->Paginator->settings = array(
            'limit' => ITEMS_PER_PAGE,
            'paramType' => 'querystring',
            'conditions' => $conditions,
        );
        $this->Product->recursive = 0;
        $this->set('products', $this->Paginator->paginate('Product'));
        $this->set('name', $name);
        $this->set('category', $category);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('category', $list_category);
    }

}
