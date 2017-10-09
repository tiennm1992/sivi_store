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
        $this->loadModel('UserLevel');
    }

    //******************************* API customer*****************************
    public function test() {
        echo 'Minh Tien';
    }

    public function sasi_summary() {
        $data = $this->request->query;

        if (!empty($data['token'])) {
            $user_data = $this->checkLogin($data['token']);
            if (empty($user_data)) {
                $this->bugError('Tài khoản không tồn tại, hoặc token hết hạn');
                die;
            }
            if ($this->checkLogin($data['token'])) {
                if (!empty($data['month']) && !empty($data['year'])) {
                    $month = $data['month'];
                    $year = $data['year'];
                } else {
                    $date = date("Y-m");
                    $date = explode('-', $date);
                    $month = $date[1];
                    $year = $date[0];
                }
                $revenue_sasi = $this->UserPosition->find('first', array(
                    'conditions' => array(
                        'UserPosition.code' => $user_data['code'],
                        'UserPosition.month' => $month,
                        'UserPosition.year' => $year,
                    )
                ));
                $best_sasi = $this->UserPosition->find('first', array(
                    'conditions' => array(
                        'UserPosition.code' => $user_data['code'],
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
                        'point_dr' => 0,
                        'point_dc' => 0,
                        'point_d' => 0,
                        'month' => $month,
                        'year' => $year
                    );
                }
                $revenue_sasi['point_dc'] = $this->UserLevel->get_point_dc($user_data['code']);
                if ($current_position == 'sasi' || $current_position == 'sasim') {
                    $revenue_sasi['point_dc'] = 0;
                    $revenue_sasi['point_dr'] = 0;
                    $revenue_sasi['point_d'] = 0;
                }
                $number_buy = $this->UserBuy->get_number_buy($user_data['code']);
                $sasi_list = $this->UserLevel->get_sub_position_list($user_data['code']);
                $number_customer = $this->Customer->get_num_customer($user_data['code']);
                $rep = array(
                    'name' => $user_data['name'],
                    'spb' => $number_buy . '',
                    'dt' => $revenue_sasi['revenue'] . '',
                    'current_level' => $current_position,
                    'best_level' => $best_position,
                    't_sasi' => $sasi_list['count'] . '',
                    'n_sasi' => $sasi_list['newbie'] . '',
                    'sasim' => $sasi_list['sasim'] . '',
                    'sasima' => $sasi_list['sasima'] . '',
                    'sasime' => $sasi_list['sasime'] . '',
                    'sasimi' => $sasi_list['sasimi'] . '',
                    'sasimo' => $sasi_list['sasimo'] . '',
                    'sasimu' => $sasi_list['sasimu'] . '',
                    'dr' => $revenue_sasi['point_dr'] . '',
                    'd' => ($revenue_sasi['point_dr'] + $revenue_sasi['point_dc']) . '',
                    'ln' => $revenue_sasi['profit'] . '',
                    'cc' => $revenue_sasi['profit_cc'] . '',
                    'tn' => '0',
                    'num_customer' => $number_customer . '',
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
                            'product_avatar' => $value['Product']['avatar'],
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
                            'date_join' => date("Y-m-d", strtotime($value['User']['created_datetime'])),
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
                    'date_join' => date("Y-m-d", strtotime($value['User']['created_datetime'])),
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
        if (!empty($data['token'])) {
            $user_data = $this->checkLogin($data['token']);
            $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
            $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
            if ($user_data) {
                if ($this->checkLogin($data['token'])) {
                    $client_data = $this->Customer->get_customer($user_data['code'], $last_id, $limit);
                    $rep = array();
                    if (!empty($client_data)) {
                        foreach ($client_data as $key => $value) {
                            $rep[$key]['client_id'] = $value['Customer']['id'];
                            $rep[$key]['client_name'] = $value['Customer']['username'];
                            $rep[$key]['client_phone'] = $value['Customer']['phone'];
                            $rep[$key]['client_address'] = $value['Customer']['address'];
                            $rep[$key]['join_date'] = $value['Customer']['created_datetime'];
                            $rep[$key]['number_buy'] = $this->UserBuy->get_number_buy_client($user_data['code'], $value['Customer']['id']);
                        }
                    }
                    $this->success('Lấy thành công danh sách', $rep);
                }
            } else {
                $this->bugError('Tài khoản không tồn tại');
            }
        } else {
            $this->echoError();
        }
    }

    public function search_customer() {
        $data = $this->request->query;
        if (!empty($data['token'])) {
            $user_data = $this->checkLogin($data['token']);
            $last_id = (!empty($data['last_id'])) ? $data['last_id'] : 0;
            $limit = (!empty($data['limit'])) ? $data['limit'] : 10;
            $content = (!empty($data['content'])) ? $data['content'] : '';
            if ($user_data) {
                if ($this->checkLogin($data['token'])) {
                    $client_data = $this->Customer->get_search($user_data['code'], $last_id, $limit, $content);
                    $rep = array();
                    if (!empty($client_data)) {
                        foreach ($client_data as $key => $value) {
                            $rep[$key]['client_id'] = $value['Customer']['id'];
                            $rep[$key]['client_name'] = $value['Customer']['username'];
                            $rep[$key]['client_phone'] = $value['Customer']['phone'];
                            $rep[$key]['client_address'] = $value['Customer']['address'];
                            $rep[$key]['join_date'] = $value['Customer']['created_datetime'];
                            $rep[$key]['number_buy'] = $this->UserBuy->get_number_buy_client($user_data['code'], $value['Customer']['id']);
                        }
                    }
                    $this->success('Lấy thành công danh sách', $rep);
                }
            } else {
                $this->bugError('Tài khoản không tồn tại');
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
                    'avatar' => $value['Product']['avatar'],
                    'category_id' => $value['Product']['category_id'],
                    'product_name' => $value['Product']['name'],
                    'gbl' => $value['Product']['price_origin'],
                    'gs' => $value['Product']['price'],
                    'km' => $value['Product']['sale'],
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
                    'avatar' => $value['Product']['avatar'],
                    'category_id' => $value['Product']['category_id'],
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
            $user_data = $this->checkLogin($data['token']);
            if (empty($user_data)) {
                $this->bugError('Tài khoản không tồn tại, hoặc token hết hạn');
                die;
            }
            if ($user_data['role'] != 'employee') {
                $this->bugError('Tài khoản này bạn không có quyền chỉnh sửa!');
                die;
            }
            if (!empty($data['address'])) {
                $user_data['address'] = $data['address'];
            }
            if (!empty($data['email'])) {
                $user_data['email'] = $data['email'];
            }
            if (!empty($data['bank_atm'])) {
                $user_data['bank_atm'] = $data['bank_atm'];
            }
            if (!empty($data['full_name'])) {
                $user_data['full_name'] = $data['full_name'];
            }
            if (!empty($data['cmtnd'])) {
                $user_data['cmtnd'] = $data['cmtnd'];
            }
            if (!empty($data['phone'])) {
                $user_data['phone'] = $data['phone'];
            }
            if (!empty($data['birthday'])) {
                $user_data['birthday'] = $data['birthday'];
            }
            if (!empty($data['gender'])) {
                $user_data['gender'] = $data['gender'];
            }
            unset($user_data['password']);
            if ($this->User->save($user_data)) {
                $this->success('Update thành công thông tin!', $rep = array());
            }
        } else {
            $this->echoError();
        }
    }

    public function edit_password() {
        $data = $this->request->query;
        if (!empty($data['username']) && !empty($data['password']) && !empty($data['new_password'])) {
            $data_user = $this->User->find('first', array('conditions' => array('User.username' => $data['username'])));
            if ($data_user) {
                $data_user = $data_user['User'];
                if ($data_user['password'] == $data['password']) {
                    $data_update = array(
                        'id' => $data_user['id'],
                        'password' => $data['new_password']
                    );
                    if ($this->User->save($data_update, array('validate' => false, 'callbacks' => false))) {
                        $rep = array(
                            'success' => 1,
                            'infor' => 'Đổi password thành công !',
                            'mess' => array(
                                'id' => $data_user['id'],
                                'role' => 'sasi',
                                'username' => $data['username']
                            ),
                        );
                        echo json_encode($rep, true);
                    } else {
                        $this->bugError('Lỗi khi đổi password !');
                    }
                } else {
                    $this->bugError('Sai mật khẩu');
                }
            } else {
                $this->bugError('Tài khoản không tồn tại');
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
            'success' => 1,
            'infor' => $data_api,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
    }

    function echoError() {
        $data_api = array(
            'success' => 0,
            'infor' => 'Thiếu tham số',
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
    }

    function bugError($infor) {
        $data_api = array(
            'success' => 0,
            'infor' => $infor,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
        die;
    }

    function success($infor, $data = array()) {
        $data_api = array(
            'success' => 1,
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
