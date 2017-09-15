<?php

class ApiSasiController extends AppController {

//class ApiController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = FALSE;
        $this->Auth->allow('display');
        $this->Auth->allow();
        //load model
        $this->loadModel('Product');
        $this->loadModel('Category');
        $this->loadModel('Subcategory');
        $this->loadModel('Slide');
        $this->loadModel('Promotion');
        $this->loadModel('Customer');
        $this->loadModel('Employee');
        $this->loadModel('UserBuy');
        $this->loadModel('UserLike');
        $this->loadModel('User');
        $this->loadModel('UserPosition');
    }

    //******************************* API customer*****************************
    public function test() {
        echo 'Minh Tien';
    }

    public function sasi_summary() {
        $data = $this->request->query;
        if (!empty($data['token'])) {
            if ($this->checkLogin($data['token'])) {
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
                $best_sasi = $this->UserPosition->find('first', array(
                    'conditions' => array(
                        'UserPosition.code' => $this->user_code,
                        'UserPosition.year' => $year,
                    ),
                    'order' => array('UserPosition.sasi_position DESC', 'UserPosition.sasi_sub_position DESC')
                ));
                $current_position = 'sasim';
                $best_position = 'sasim';
                if (!empty($best_sasi)) {
                    $best_position = $this->UserPosition->convert_position($best_sasi['UserPosition']['sasi_position'], $best_sasi['UserPosition']['sasi_position']);
                }
                if (!empty($revenue_sasi)) {
                    $revenue_sasi = $revenue_sasi['UserPosition'];
                    $current_position = $this->UserPosition->convert_position($revenue_sasi['sasi_position'], $revenue_sasi['sasi_sub_position']);
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
                $number_customer = $this->Customer->get_num_customer($this->user_code);
                $rep = array(
                    'name' => '',
                    'spb' => '',
                    'dt' => '',
                    'current_level' => '',
                    'best_level' => '',
                    't_sasi' => '',
                    'n_sasi' => '',
                    't_kh' => '',
                    'sasim' => '',
                    'sasima' => '',
                    'sasime' => '',
                    'sasimi' => '',
                    'sasimo' => '',
                    'sasimu' => '',
                    'dr' => '',
                    'd' => '',
                    'ln' => '',
                    'cc' => '',
                    'tn' => '',
                );
            }
            $this->success('Lấy thành công danh sách', $rep);
        } else {
            $this->echoError();
        }
    }

    public function order_list() {
        $data = $this->request->query;
        if (!empty($data['token'])) {
            $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
            $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
            $month = (!empty($data['month'])) ? $data['month'] : 0;
            if (empty($month)) {
                $this->bugError('thieeu bien month');
            }
            $user_data = $this->checkLogin($data['token']);
            if ($user_data) {
                $conditions = array(
                    'UserBuy.status' => 2,
                    'UserBuy.code' => $user_data['code'],
                );
                $end_date = date("Y-") . $month . "-31 23:23:23";
                $start_date = date("Y-") . $month . "-1 00:00:00";
                $conditions['UserBuy.date <='] = $end_date;
                $conditions['UserBuy.date >'] = $start_date;
                if (!empty($last_id)) {
                    $conditions['UserBuy.id <'] = $last_id;
                }
                $this->UserBuy->recursive = -1;
                $buy_data = $this->UserBuy->find('all', array(
                    'fields' => array('Customer.*', 'Product.*', 'UserBuy.*', 'User.*',),
                    'conditions' => $conditions,
                    'limit' => $limit,
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
                ));
                $rep = array();
                if (!empty($buy_data)) {
                    foreach ($buy_data as $key => $value) {
                        $rep[$key] = array(
                            'id' => $value['UserBuy']['id'],
                            'product_name' => $value['Product']['name'],
                            'sasi_name' => $value['User']['name'],
                            'phone_sasi' => $value['User']['phone'],
                            'address' => $value['User']['address'],
                            'customer_name' => $value['Customer']['name'],
                            'customer_phone' => $value['Customer']['phone'],
                            'customer_address' => $value['Customer']['address'],
                            'date' => $value['UserBuy']['date'],
                        );
                    }
                }
                $this->success('Lấy thành công danh sách', $rep);
            } else {
                $this->bugError('Người dùng chưa đăng nhập, token không tồn tại hoặc sai token');
            }
        } else {
            $this->echoError();
        }
    }

    public function sasi_list() {
        $data = $this->request->query;
        if (!empty($data['token'])) {
            $user_data = $this->checkLogin($data['token']);
            if ($user_data) {
                $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
                $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
                $conditions = array();
                $conditions['User.role'] = 'employee';
                $conditions['User.sale_id_protected'] = $user_data['code'];
                if (!empty($last_id)) {
                    $conditions['User.id <'] = $last_id;
                }
                $sasi_data = $this->User->find('all', array(
                    'conditions' => $conditions,
                    'limit' => $limit,
                    'order' => array("User.id DESC")
                ));
                $rep = array();
                if (!empty($sasi_data)) {
                    foreach ($sasi_data as $key => $value) {
                        $rep[$key] = array(
                            'id' => $value['User']['id'],
                            'sasi_name' => $value['User']['name'],
                            'sasi_phone' => $value['User']['phone'],
                            'sasi_code' => $value['User']['code'],
                            'date_join' => $value['User']['created_datetime'],
                        );
                    }
                }
                $this->success('Lấy thành công danh sách', $rep);
            } else {
                $this->bugError('Người dùng chưa đăng nhập, token không tồn tại hoặc sai token');
            }
        } else {
            $this->echoError();
        }
    }

    public function search_sasi() {
        $data = $this->request->query;
        $sasi_name = (!empty($data['sasi_name'])) ? $data['sasi_name'] : '';
        $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
        $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
        $conditions = array();
        $conditions['User.role'] = 'employee';
        if (!empty($sasi_name)) {
            $conditions[] = " User.name LIKE '%{$sasi_name}%'";
        }
        if (!empty($last_id)) {
            $conditions[] = 'User.id < ' . $last_id;
        }
        $sasi_data = $this->User->find('all', array(
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => array("User.id DESC")
        ));
        $rep = array();
        if (!empty($sasi_data)) {
            foreach ($sasi_data as $key => $value) {
                $rep[$key] = array(
                    'id' => $value['User']['id'],
                    'sasi_name' => $value['User']['name'],
                    'sasi_phone' => $value['User']['phone'],
                    'sasi_code' => $value['User']['code'],
                    'date_join' => $value['User']['created_datetime'],
                );
            }
        }
        $this->success('Lấy thành công danh sách', $rep);
    }

    public function sasi_detail() {
        $data = $this->request->query;
        $sasi_id = (!empty($data['sasi_id'])) ? $data['sasi_id'] : 10;
        $conditions = array();
        $conditions['User.role'] = 'employee';
        if (!empty($sasi_id)) {
            $conditions['User.id '] = $sasi_id;
        } else {
            $this->bugError('Thieu tham so sasi_id');
        }
        $sasi_data = $this->User->find('all', array(
            'conditions' => $conditions,
        ));
        $rep = array();
        if (!empty($sasi_data)) {
            foreach ($sasi_data as $key => $value) {
                $rep[$key] = $value['User'];
                unset($rep[$key]['password']);
                unset($rep[$key]['token']);
            }
        }
        $this->success('Lấy thành công danh sách', $rep);
    }

    public function customer_list() {
        $data = $this->request->query;
        $user_data = $this->checkLogin($data['token']);
        if ($user_data) {
            if ($this->checkLogin($data['token'])) {
                $client_data = $this->Customer->get_customer($user_data['code']);
            }
        } else {
            $this->echoError();
        }
    }

    public function product_list() {
        $data = $this->request->query;
        $conditions = array();
        $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
        $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
        if (!empty($last_id)) {
            $conditions[] = 'Product.id < ' . $last_id;
        }
        $product_list = $this->Product->find('all', array(
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => array('Product.id DESC')
        ));
        $rep = array();
        if (!empty($product_list)) {
            foreach ($product_list as $key => $value) {
                $rep[$key] = array(
                    'id' => $value['Product']['id'],
                    'category_id' => $value['Product']['id'],
                    'product_name' => $value['Product']['name'],
                    'gbl' => $value['Product']['price_origin'],
                    'gs' => $value['Product']['sale'],
                    'c0' => $value['Product']['c0'],
                    'c1' => $value['Product']['partner_price'],
                    'c2' => $value['Product']['employee_price'],
                );
            }
        }
        $this->success('Lấy thành công danh sách', $rep);
    }

    public function search_product() {
        $data = $this->request->query;
        $conditions = array();
        $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
        $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
        $product_name = (!empty($data['product_name'])) ? $data['product_name'] : '';
        if (!empty($last_id)) {
            $conditions[] = 'Product.id < ' . $last_id;
        }
        if (!empty($product_name)) {
            $conditions[] = " Product.name LIKE '%{$product_name}%'";
        }
        $product_list = $this->Product->find('all', array(
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => array('Product.id DESC')
        ));
        $rep = array();
        if (!empty($product_list)) {
            foreach ($product_list as $key => $value) {
                $rep[$key] = array(
                    'id' => $value['Product']['id'],
                    'category_id' => $value['Product']['id'],
                    'product_name' => $value['Product']['name'],
                    'gbl' => $value['Product']['price_origin'],
                    'gs' => $value['Product']['sale'],
                    'c0' => $value['Product']['c0'],
                    'c1' => $value['Product']['partner_price'],
                    'c2' => $value['Product']['employee_price'],
                );
            }
        }
        $this->success('Lấy thành công danh sách', $rep);
    }

    public function edit_infor() {
        $data = $this->request->query;
        if (!empty($data['token'])) {
            if ($this->checkLogin($data['token'])) {
                
            }
        } else {
            $this->echoError();
        }
    }

    public function guide() {
        $data = $this->request->query;
        if (!empty($data['token'])) {
            if ($this->checkLogin($data['token'])) {
                
            }
        } else {
            $this->echoError();
        }
    }

    public function checkLogin($token) {
        $data = $this->User->find('first', array('conditions' => array('token' => $token)));
        if ($data) {
            return $data['User'];
        } else {
            return 0;
        }
    }

    function echoData($data) {
        $data_api = array();
        foreach ($data as $key => $value) {
            $data_api[] = $value['Product'];
        }
        $data_api = array(
            'success' => 'true',
            'infor' => $data_api,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
    }

    function echoError() {
        $data_api = array(
            'success' => 'false',
            'infor' => 'Thiếu tham số',
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
    }

    function bugError($infor) {
        $data_api = array(
            'success' => 'false',
            'infor' => $infor,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
        die;
    }

    function success($infor, $data = array()) {
        $data_api = array(
            'success' => 'success',
            'infor' => $infor,
            'data' => $data,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
        die;
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
