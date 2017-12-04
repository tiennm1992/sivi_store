<?php

class ApiController extends AppController {

//class ApiController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = FALSE;
        $this->Auth->allow('display');
        $this->Auth->allow(
                'social_action', 'test', 'get_customer', 'get_revenue', 'cancel_order', 'edit_profile', 'like_product', 'get_buy', 'read_late', 'check_buy', 'buy_item', 'login', 'user_infor', 'sign_up', 'home', 'product', 'suggest_product', 'get_product', 'get_category', 'get_subcategory', 'get_slide', 'get_top', 'check_view', 'search', 'post_relate', 'menu_tab', 'promotion'
        );
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
        $this->loadModel('SocialCount');
    }

    //******************************* API customer*****************************
    public function test() {
        echo 'Minh Tien';
    }

    //api get customer
    public function get_customer() {
        $data = $this->request->query;
        if (!empty($data['user_id'])) {
            $date_user = $this->User->find('first', array('conditions' => array('User.id' => $data['user_id'])));
            if ($date_user) {
                $date_customer = $this->Customer->find('all', array('conditions' => array('Customer.employee_code' => $date_user['User']['code'])));
                $arr = array();
                foreach ($date_customer as $key => $value) {
                    $arr[$key]['id'] = $value['Customer']['id'];
                    $arr[$key]['username'] = $value['Customer']['username'];
                    $arr[$key]['phone'] = $value['Customer']['phone'];
                    $arr[$key]['address'] = $value['Customer']['address'];
                    $arr[$key]['employee_code'] = $value['Customer']['employee_code'];
                }
                $rep = array(
                    'success' => 'true',
                    'infor' => 'Lấy thông tin thành công',
                    'mess' => $arr,
                );
                echo json_encode($rep, true);
            } else {
                $this->bugError('Tài khoản nhân viên không tồn tại');
            }
        } else {
            $this->echoError();
        }
    }

    //get infor employee
    public function get_infor_employee() {
        $data = $this->request->query;
        if (!empty($data['token']) && !empty($data['user_id'])) {
            if ($this->checkLogin($data['token'])) {
                $data_user = $this->Customer->find('all', array('conditions' => array('Customer.id' => $data['user_id'])));
                $data_user = $data_user[0]['Customer'];
                $list_product_id = array();
                $list_product = $this->UserBuy->find('all', array('conditions' => array('UserBuy.customer_id' => $data['user_id'])));
                foreach ($list_product as $key => $value) {
                    $list_product_id[] = $value['UserBuy']['product_id'];
                }
                $infor_user = array(
                    'user_name' => $data_user['username'],
                    'phone' => $data_user['phone'],
                    'employee_code' => $data_user['employee_code'],
                    'list_id_product' => $list_product_id
                );
                $rep = array(
                    'success' => 'true',
                    'mess' => $infor_user
                );
                echo json_encode($rep);
            } else {
                $this->bugError('Người dùng chưa login');
            }
        } else {
            $this->echoError();
        }
    }

    //api get revenue
    public function get_revenue() {
        $data = $this->request->query;
        if (!empty($data['user_id'])) {
            $date_user = $this->User->find('first', array('conditions' => array('User.id' => $data['user_id'])));
            if ($date_user) {
                $end_date = date("Y-m-d H:s:i");
                $start_date = date("Y-m") . "-1 00:00:00";
                $conditions = array(
                    'UserBuy.code' => $date_user['User']['code'],
                    "UserBuy.date <= '{$end_date}'",
                    "UserBuy.date > '{$start_date}'",
                );
                $sum_revenue = $this->UserBuy->find('all', array(
                    'conditions' => $conditions,
                    'fields' => array('sum(UserBuy.revenue) as total_sum')
                ));
                $sum_revenue = $sum_revenue[0][0]['total_sum'];
                $sum_revenue = number_format($sum_revenue, 0, ',', '.');
                $data_revenue = $this->UserBuy->find('all', array(
                    'fields' => array('UserBuy.*', 'Product.*'),
                    'conditions' => $conditions,
                    'join' => array(
                        array(
                            'table' => 'products',
                            'alias' => 'Product',
                            'type' => 'inner',
                            'conditions' => array(
                                'Product.id = UserBuy.product_id'
                            )
                        ))
                ));
                $arr = array();
                foreach ($data_revenue as $key => $value) {
                    $arr[$key] = $value['UserBuy'];
                    $arr[$key]['product_name'] = $value['Product']['name'];
                    $arr[$key]['product_avatar'] = $value['Product']['avatar'];
                    $arr[$key]['price_sale'] = number_format($arr[$key]['price_sale'], 0, ',', '.');
                    $arr[$key]['revenue'] = number_format($arr[$key]['revenue'], 0, ',', '.');
                }
                $rep = array(
                    'success' => 'true',
                    'infor' => 'Lấy thông tin thành công',
                    'sum_revenue' => $sum_revenue,
                    'mess' => $arr,
                );
                echo json_encode($rep, true);
            } else {
                $this->bugError('Tài khoản nhân viên không tồn tại');
            }
        } else {
            $this->echoError();
        }
    }

    //api delete order buy item
    public function cancel_order() {
        $data = $this->request->query;
        if (!empty($data['token']) && !empty($data['customer_id']) && !empty($data['product_id'])) {
            if ($this->checkLogin($data['token'])) {
                if ($this->Product->exists($data['product_id'])) {
                    $conditions = array(
                        'UserBuy.customer_id' => $data['customer_id'],
                        'UserBuy.product_id' => $data['product_id']
                    );
                    if (!empty($data['date'])) {
                        $conditions['UserBuy.date'] = $data['date'];
                    }
                    $data = $this->UserBuy->find('first', $conditions);
                    if (!empty($data['UserBuy']['status']) && ($data['UserBuy']['status'] >= 1)) {
                        $this->UserBuy->create();
                        $this->UserBuy->deleteAll($conditions);
                        $this->success('Hủy thành công đơn hàng');
                    } else {
                        $this->bugError('Đơn hàng đã được duyệt không hủy được đơn hàng');
                    }
                } else {
                    $this->bugError('Sản phẩm không tồn tại');
                }
            } else {
                $this->bugError('Người dùng chưa login');
            }
        } else {
            $this->echoError();
        }
    }

    //api edit profile client
    public function edit_profile() {
        $data = $this->request->query;
        if (!empty($data['id']) && !empty($data['password']) && !empty($data['employee_code']) && !empty($data['phone']) && !empty($data['token'])) {
            if ($this->checkLogin($data['token'])) {
                $data_user = $this->Customer->find('first', array('conditions' => array('Customer.id' => $data['id'])));
                if ($data_user) {
                    $data_change = array();
                    $data_change['id'] = $data['id'];
                    if ($data['password'] != $data_user['Customer']['password']) {
                        if (!empty($data['old_password']) && ($data['old_password'] == $data_user['Customer']['password'])) {
                            $data_change['password'] = $data['password'];
                        } else {
                            $this->bugError('Mật khẩu hiện tại nhập không đúng');
                        }
                    }
                    if (!empty($data['old_password']) && ($data['old_password'] != $data_user['Customer']['password'])) {
                        $this->bugError('Password cũ bị sai');
                    }
                    if ($data['employee_code'] != $data_user['Customer']['employee_code']) {
                        $data_change['employee_code'] = $data['employee_code'];
                    }
                    if ($data['phone'] != $data_user['Customer']['phone']) {
                        $data_change['phone'] = $data['phone'];
                    }
                    if (isset($data['address']) && ($data['address'] != $data_user['Customer']['address'])) {
                        $data_change['address'] = $data['address'];
                    }

                    $this->Customer->create;
                    if ($this->Customer->save($data_change)) {
                        $this->success('Thay đổi thông tin thành công');
                    } else {
                        $this->bugError('Thay đổi thông tin thất bại');
                    }
                } else {
                    $this->bugError('Tài khoản không tồn tại');
                }
            } else {
                $this->bugError('Người dùng chưa đăng nhập !!!');
            }
        } else {
            $this->echoError();
        }
    }

    //api like
    public function like_product() {
        $data = $this->request->query;
        $data['status'] = (isset($data['status'])) ? $data['status'] : 0;
        if (!empty($data['user_id']) && !empty($data['product_id'])) {
            if ($this->Product->exists($data['product_id'])) {
                $check = $this->UserLike->status($data['user_id'], $data['product_id'], $data['status']);
                if ($check) {
                    $rep = array(
                        'success' => 'true',
                        'infor' => 'thành công',
                    );
                    echo json_encode($rep, true);
                } else {
                    $rep = array(
                        'success' => 'false',
                        'infor' => 'That bai',
                    );
                    echo json_encode($rep, true);
                }
            } else {
                $this->bugError('Sản phẩm không tồn tại');
            }
        } else {
            $this->echoError();
        }
    }

    //đăng nhập vào hệ thống
    public function login() {
        $data = $this->request->query;
        $data['role'] = !empty($data['role']) ? $data['role'] : 'customer';
        if (!empty($data['username']) && !empty($data['password'])) {
            $username = $data['username'];
            $data_user = $this->Customer->find('all', array('conditions' => array('Customer.username' => $username)));
            if (!empty($data['role']) && $data['role'] == 'sasi') {
                $data_user = $this->User->find('all', array('conditions' => array('User.username' => $username)));
                if ($data_user) {
                    $data_user = $data_user[0]['User'];
                    if ($data_user['password'] == $data['password']) {
                        $token = $this->generateRandomString(16);
                        $data_update = array(
                            'id' => $data_user['id'],
                            'token' => $token
                        );
                        if ($this->User->save($data_update)) {
                            $sale_name_protected = '';
                            if (!empty($data_user['sale_id_protected'])) {
                                $user_protected = $this->User->find('first', array('conditions' => array('User.code' => $data_user['sale_id_protected'])));
                                $sale_name_protected = !empty($user_protected['User']['name']) ? $user_protected['User']['name'] : '';
                            }
                            $rep = array(
                                'success' => 'true',
                                'infor' => 'Login thành công',
                                'mess' => array(
                                    'id' => $data_user['id'],
                                    'role' => 'sasi',
//                                    'role' => $data_user['role'],
                                    'token' => $token,
                                    'username' => $data_user['username'],
                                    'name' => $data_user['name'],
                                    'avatar' => $data_user['avatar'],
                                    'phone' => $data_user['phone'],
                                    'birthday' => $data_user['birthday'],
                                    'gender' => $data_user['gender'],
                                    'address' => $data_user['address'],
                                    'email' => $data_user['email'],
                                    'cmtnd' => $data_user['cmtnd'],
                                    'bank_atm' => $data_user['bank_atm'],
                                    'date_join' => date("Y-m-d", strtotime($data_user['created_datetime'])),
                                    'sale_id_protected' => $data_user['sale_id_protected'],
                                    'sale_name_protected' => $sale_name_protected,
                                ),
                            );
                            echo json_encode($rep, true);
                        } else {
                            $this->bugError('Lỗi khi đăng nhập');
                        }
                    } else {
                        $this->bugError('Sai mật khẩu');
                    }
                } else {
                    $this->bugError('Tài khoản không tồn tại');
                }
            } else if (!empty($data_user) && ( $data['role'] != 'sasi')) {
                $data_user = $data_user[0]['Customer'];
                $data_user['role'] = 'customer';
                if ($data_user['password'] == $data['password']) {
                    $token = $this->generateRandomString(16);
                    $data_update = array(
                        'id' => $data_user['id'],
                        'token' => $token
                    );
                    $this->Customer->create();
                    if ($this->Customer->save($data_update)) {
                        $rep = array(
                            'success' => 'true',
                            'infor' => 'Login thành công',
                            'mess' => array(
                                'id' => $data_user['id'],
                                'role' => $data_user['role'],
                                'token' => $token,
                                'username' => $data_user['username'],
                            ),
                        );
                        echo json_encode($rep, true);
                    } else {
                        $this->bugError('Lỗi khi đăng nhập');
                    }
                } else {
                    $this->bugError('Sai mật khẩu');
                }
            } else {
                $this->bugError('Đăng nhập không hợp lệ, Tài khoản không đúng hoặc không tồn tại !');
            }
        } else {
            $this->echoError();
        }
    }

    // mua sản phẩm
    public function buy_item() {
        $data = $this->request->query;
        if (!empty($data['token']) && !empty($data['user_id']) && !empty($data['product_id'])) {
            if ($this->checkLogin($data['token'])) {
                if ($this->Product->exists($data['product_id'])) {
                    $product_data = $this->Product->find('first', array('conditions' => array('Product.id' => $data['product_id'])));
                    $product_user = $this->Customer->find('first', array('conditions' => array('Customer.id' => $data['user_id'])));
                    if (isset($data['number_product']) && !empty($data['number_product'])) {
                        $num_product = $data['number_product'];
                    } else {
                        $num_product = 1;
                    }
                    $arr = array(
                        'customer_id' => $data['user_id'],
                        'code' => $product_user['Customer']['employee_code'],
//                        'code' => $data['sale_id'],
                        'product_id' => $data['product_id'],
                        'price_origin' => $product_data['Product']['price_origin'],
                        'price_sale' => $product_data['Product']['price'],
                        'c0' => $product_data['Product']['c0'],
                        'partner_price' => $product_data['Product']['partner_price'],
                        'employee_price' => $product_data['Product']['employee_price'],
                        'revenue' => $product_data['Product']['price'] - $product_data['Product']['price_origin'],
                        'date' => date("Y-m-d H:i:s"),
                        'number_product' => $num_product
                    );
//                    $this->UserBuy->create();
                    if ($this->UserBuy->save($arr)) {
                        $this->UserBuy->clear();
                        $this->calculate_money($arr['code']);
                        $rep = array(
                            'success' => 'true',
                            'infor' => 'Mua sản phẩm thành công',
                        );
                        echo json_encode($rep, true);
                    } else {
                        $this->bugError('Mua sản phầm thất bại');
                    }
                } else {
                    $this->bugError('Sản phẩm không tồn tại');
                }
            } else {
                $this->bugError('Người dùng chưa login');
            }
        } else {
            $this->echoError();
        }
    }

    public function calculate_money($user_code) {
        $end_date = date("Y-m") . "-31 00:00:00";
        $start_date = date("Y-m") . "-1 00:00:00";
        $cond = array(
//            'UserBuy.status' => 2,
            'UserBuy.code' => $user_code,
            "UserBuy.date <= '{$end_date}'",
            "UserBuy.date > '{$start_date}'",
        );
        $sum_revenue = $this->UserBuy->find('all', array(
            'conditions' => $cond,
            'fields' => array('sum(UserBuy.number_product) as total_product',),
        ));
        $data_payment = $this->UserBuy->find('all', array(
            'conditions' => $cond,
            'fields' => array("UserBuy.*"),
        ));
        $total_product = $sum_revenue[0][0]['total_product'];
        if ($total_product > 2 && $total_product < 10) {
            $this->update_money(1, $data_payment);
        } elseif ($total_product > 9) {
            $this->update_money(2, $data_payment);
        } else {
            $this->update_money(0, $data_payment);
        }
    }

    public function update_money($level = 0, $data = array()) {
        foreach ($data as $key => $value) {
            $revenue = 0;
            if ($level == 1) {
//                $revenue = $value['UserBuy']['price_sale'] - ($value['UserBuy']['price_sale'] - $value['UserBuy']['partner_price']) * 0.5;
                $revenue = $value['UserBuy']['price_sale'] - $value['UserBuy']['partner_price'];
            } elseif ($level == 2) {
                $revenue = $value['UserBuy']['price_sale'] - $value['UserBuy']['employee_price'];
//                $revenue = $value['UserBuy']['price_sale'] - ($value['UserBuy']['price_sale'] - $value['UserBuy']['employee_price']) * 0.7;
            }
//            else {
//                $revenue = $value['UserBuy']['price_sale'] - $value['UserBuy']['price_origin'];
//            }
            if (!empty($value['UserBuy']['number_product'])) {
                $revenue = $revenue * $value['UserBuy']['number_product'];
            }
            $value['UserBuy']['revenue'] = $revenue;
            $this->UserBuy->save($value['UserBuy']);
            $this->UserBuy->clear();
        }
    }

    public function checkLogin($token) {
        $data = $this->Customer->find('all', array('conditions' => array('token' => $token)));
        if ($data) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_customer_Login($token) {
        $data = $this->Customer->find('first', array('conditions' => array('token' => $token)));
        if ($data) {
            return $data['Customer'];
        } else {
            return 0;
        }
    }

    //lay list san phan da mua
    public function get_buy() {
        $user_id = $this->request->query('user_id');
        if (isset($user_id)) {
            $data = $this->UserBuy->find('all', array('conditions' => array('UserBuy.customer_id' => $user_id)));
            $rep = array();
            foreach ($data as $key => $value) {
                if (!empty($value['Product']['id'])) {
                    $arr_tmp = array(
                        'product_id' => $value['Product']['id'],
                        'name' => $value['Product']['name'],
                        'title' => $value['Product']['title'],
                        'avatar' => $value['Product']['avatar'],
                        'price' => number_format($value['Product']['price'], 0, ',', '.'),
                        'date' => $value['UserBuy']['date'],
                        'number_product' => $value['UserBuy']['number_product'],
                    );
                    $rep[] = $arr_tmp;
                }
            }
            $this->success($rep);
        } else {
            $this->bugError('Thiếu biến');
        }
    }

    //kiem tra san phanm da dc mua chua
    public function check_buy() {
        $user_id = $this->request->query('user_id');
        $product_id = $this->request->query('product_id');
        $data = $this->UserBuy->find('all', array('conditions' => array('UserBuy.customer_id' => $user_id, 'UserBuy.product_id' => $product_id)));
        if ($data) {
            $this->bugError('Sản phẩm đã được mua');
        } else {
            $this->success('Sản phẩm chưa được mua');
        }
    }

    //lấy thông tin user
    public function user_infor() {
        $data = $this->request->query;
        if (!empty($data['role']) && ($data['role'] == 'employee') && !empty($data['user_id'])) {
            $data_user = $this->User->find('all', array('conditions' => array('User.id' => $data['user_id'])));
            if ($data_user) {
                $data_user = $data_user[0]['User'];
                $infor_user = array(
                    'user_name' => $data_user['username'],
                    'address' => $data_user['address'],
//                    'phone' => $data_user['phone'],
                    'phone' => !empty($data_user['phone']) ? $data_user['phone'] : '',
                    'employee_code' => $data_user['code'],
                );
                $rep = array(
                    'success' => 'true',
                    'mess' => $infor_user
                );
                echo json_encode($rep);
                die;
            } else {
                $this->bugError('Tài khoản nhân viên không tồn tại');
            }
        }
        if (!empty($data['token']) && !empty($data['user_id'])) {
            if ($this->checkLogin($data['token'])) {
                $data_user = $this->Customer->find('all', array('conditions' => array('Customer.id' => $data['user_id'])));
                $data_user = $data_user[0]['Customer'];
                $list_product_id = array();
                $list_product = $this->UserBuy->find('all', array('conditions' => array('UserBuy.customer_id' => $data['user_id'])));
                foreach ($list_product as $key => $value) {
                    $list_product_id[] = $value['UserBuy']['product_id'];
                }
                $infor_user = array(
                    'user_name' => $data_user['username'],
                    'address' => $data_user['address'],
                    'phone' => !empty($data_user['phone']) ? $data_user['phone'] : '',
                    'employee_code' => $data_user['employee_code'],
                    'list_id_product' => $list_product_id
                );
                $rep = array(
                    'success' => 'true',
                    'mess' => $infor_user
                );
                echo json_encode($rep);
            } else {
                $this->bugError('Người dùng chưa login');
            }
        } else {
            $this->echoError();
        }
    }

    //đăng kí user
    public function sign_up() {
        $data = $this->request->query;
        if (!empty($data['username']) && !empty($data['password'])) {
//        if (!empty($data['username']) && !empty($data['password']) && !empty($data['employee_code']) && !empty($data['phone'])) {
            $check_account = $this->Customer->checkUser($data['username']);
            if (!$check_account) {
                $this->bugError('Tài khoản đã tồn tại');
            }
            $check_account2 = $this->User->find('all', array('conditions' => array('User.username' => $data['username'])));
            if ($check_account2) {
                $this->bugError('Tài khoản đã tồn tại');
            }
            $employee_code = $this->User->checkExitsCode($data['employee_code']);
            if (!$employee_code) {
                $this->bugError('Mã nhân viên không tồn tại');
            }
            $address = (isset($data['address'])) ? $data['address'] : '';
            $arr = array(
                'username' => $data['username'],
                'password' => $data['password'],
                'phone' => $data['phone'],
                'address' => $address,
                'employee_code' => !empty($data['employee_code']) ? $data['employee_code'] : 0,
                'created_datetime' => date("Y-m-d H:i:s")
            );
            $this->Customer->create;
            if ($this->Customer->save($arr)) {
                $this->success('Đăng kí thành công');
            } else {
                $this->bugError('Đăng kí thất bại');
            }
        } else {
            $this->echoError();
        }
    }

    //***************************************************Api general************************************
    //api get product lấy sau
    public function read_late() {
        $product_list = $this->request->query('list');
        if (!isset($product_list)) {
            $this->bugError('Thiếu tham số');
        }
        $product_list = explode('|', $product_list);
        $arr1 = array(
            'Product.id',
            'Product.name',
            'Product.title',
            'Product.description',
            'Product.avatar',
            'Product.price',
        );
        $rep = array();
        foreach ($product_list as $key => $value) {
            if (!$this->Product->exists($value)) {
                $this->bugError('product_id not exits');
            }
            $arr = array(
                'conditions' => array(
                    'Product.id' => $value
                ),
                'fields' => $arr1
            );
            $data = $this->Product->find('first', $arr);
            foreach ($data as $key1 => $value1) {
                $data[$key1]['price'] = number_format($data[$key1]['price'], 0, ',', '.');
            }
            $rep[] = $data;
        }
        $this->echoData($rep);
    }

    //api lay chia tiet cua san pham
    public function suggest_product($page = 0) {
        $page1 = $this->request->query('page');
        $page = (isset($page1)) ? $page1 : $page;
        $arr1 = array(
            'Product.id',
            'Product.name',
            'Product.title',
            'Product.description',
            'Product.avatar',
            'Product.price',
        );
        $arr = array(
            'limit' => 10, //int
            'page' => $page, //int
            'order' => 'Product.sort DESC',
            'fields' => $arr1
        );
        $data = $this->Product->find('all', $arr);
        foreach ($data as $key1 => $value1) {
            $data[$key1]['Product']['price'] = number_format($data[$key1]['Product']['price'], 0, ',', '.');
        }
        $this->echoData($data);
    }

    public function get_product($id = 0) {
        $id1 = $this->request->query('id');
        $id = (isset($id1)) ? $id1 : $id;
        if (!$this->Product->exists($id)) {
            $this->bugError('product_id not exits');
        }

        $user_id = $this->request->query('user_id');
        if (!isset($user_id)) {
            $user_id = 0;
        }
        $data = $this->Product->getDetailProduct($id, $user_id);
        $data = array(
            'success' => 'true',
            'infor' => $data,
        );
        $data = json_encode($data, true);
        echo ($data);
    }

    public function get_category() {
        $this->Category->unbindModel(array('hasMany' => array('Product', 'Subcategory')), true);
        $data = $this->Category->find("all");
        $category = array();
        foreach ($data as $key => $value) {
            $category[$key] = $value['Category'];
        }
        $category = json_encode($category, true);
        echo $category;
    }

    //lấy danh mục con
    public function get_subcategory() {
        $category = $this->request->query('category_id');
        if ($category) {
            $this->Subcategory->unbindModel(array('hasMany' => array('Category')), true);
            $arr = array(
                'conditions' => array(
                    'Subcategory.category_id' => $category
                )
            );
            $data = $this->Subcategory->find("all", $arr);
            $subcategory = array();
            foreach ($data as $key => $value) {
                $subcategory[$key] = $value['Subcategory'];
            }
            $subcategory = json_encode($subcategory, true);
            echo $subcategory;
        } else {
            $this->echoError();
        }
    }

    //lay slide ảnh
    public function get_slide() {
        $arr = array(
            'limit' => 5,
            'order' => "Slide.id DESC",
        );
        $data = $this->Slide->find("all", $arr);
        $slide = array();
        foreach ($data as $key => $value) {
            $slide[$key] = $value['Slide'];
            $slide[$key]['image'] = 'http://sivistore.com/' . $value['Slide']['image'];
        }
        $slide = json_encode($slide, true);
        echo $slide;
    }

    public function home($page = 1) {
        $data = $this->Product->find('all');
        foreach ($data as $key1 => $value1) {
            $data[$key1]['Product']['price'] = number_format($data[$key1]['Product']['price'], 0, ',', '.');
        }
        pr($data);
    }

    // lay sp ban chạy
    public function get_top($page = 0) {
        $page1 = $this->request->query('page');
        $page = (isset($page1)) ? $page1 : $page;
        $arr1 = array(
            'Product.id',
            'Product.name',
            'Product.title',
            'Product.description',
            'Product.avatar',
            'Product.price',
        );
        $arr = array(
            'limit' => 10, //int
            'page' => $page, //int
            'order' => 'Product.user_view DESC',
            'fields' => $arr1
        );
        $data = $this->Product->find('all', $arr);
        foreach ($data as $key1 => $value1) {
            $data[$key1]['Product']['price'] = number_format($data[$key1]['Product']['price'], 0, ',', '.');
        }
        $this->echoData($data);
    }

    public function check_view() {
        $product_id = $this->request->query('product_id');
        if ($product_id) {
            if (!$this->Product->exists($product_id)) {
                $this->bugError('Không tồn tại product_id này');
            }
            $this->Product->updateAll(array('Product.user_view' => 'Product.user_view + 1'), array('Product.id' => $product_id));
            echo 'Done';
        } else {
            echo 'Chans ddowi';
        }
    }

    //social count : like, favorite, comment
    public function social_action() {
        $data = $this->request->query;
        if (empty($data['token']) || empty($data['product_id']) || empty($data['action'])) {
            $this->echoError();
        }
        if ($data['action'] != 'like' && $data['action'] != 'favorite') {
            $this->bugError('Action khong ton tai !');
        }
        $data['type'] = isset($data['type']) ? $data['type'] : 1;
        if ($data['type'] != 1 && $data['type'] != 0) {
            $this->bugError('Trang thai cua type khong phu hop !');
        }
        $user_data = $this->get_customer_Login($data['token']);
        if (!$user_data) {
            $this->bugError('Người dùng chưa login');
        }
        if (!$this->Product->exists($data['product_id'])) {
            $this->bugError('Sản phẩm không tồn tại');
        }
        $product_data = $this->Product->find('first', array('conditions' => array('Product.id' => $data['product_id'])));
        if ($data['action'] == 'like') {
            $this->SocialCount->social_action($user_data['id'], $data['product_id'], $data['type'], $data['action']);
            if ($data['type']) {
                $product_data['Product']['user_like'] +=1;
                $this->Product->save($product_data['Product']);
            } else {
                if (!empty($product_data['Product']['user_like'])) {
                    $product_data['Product']['user_like'] -=1;
                    $this->Product->save($product_data['Product']);
                }
            }
            $this->success('Like san pham thanh cong.');
        } elseif ($data['action'] == 'favorite') {
            $this->SocialCount->social_action($user_data['id'], $data['product_id'], $data['type'], $data['action']);
            if ($data['type']) {
                $product_data['Product']['user_favorite'] +=1;
                $this->Product->save($product_data['Product']);
            } else {
                if (!empty($product_data['Product']['user_favorite'])) {
                    $product_data['Product']['user_favorite'] -=1;
                    $this->Product->save($product_data['Product']);
                }
            }
            $this->success('Favorite san pham thanh cong.');
        }
        $this->bugError('Thưc hiện không thành công !');
    }

    public function search() {
        $content = $this->request->query('content');
        if (!empty($content)) {
            $arr1 = array(
                'Product.id',
                'Product.name',
                'Product.title',
                'Product.description',
                'Product.avatar',
                'Product.price',
            );
            $arr = array(
                'fields' => $arr1,
                'limit' => 5,
                // 'order' => 'Product.id DESC',
                'conditions' => array(
                    'OR' => array(
                        'Product.title LIKE' => "%" . $content . "%",
                        'Product.name LIKE' => "%" . $content . "%",
                        'Product.description LIKE' => "%" . $content . "%",
                    )
                )
            );
            $data = $this->Product->find('all', $arr);
            foreach ($data as $key1 => $value1) {
                $data[$key1]['Product']['price'] = number_format($data[$key1]['Product']['price'], 0, ',', '.');
            }
            $this->echoData($data);
        } else {
            $this->echoError();
        }
    }

    public function post_relate() {
        $product_id = $this->request->query('product_id');
        if (!empty($product_id)) {
            $data = $this->Product->find('all', array('conditions' => array('Product.id' => $product_id)));
            if (!empty($data[0]['Product'])) {
                $arr = array(
                    'conditions' => array(
                        'Product.category_id' => $data[0]['Product']['category_id'],
                    ),
                    'limit' => 5
                );
                $data = $this->Product->find('all', $arr);
                foreach ($data as $key1 => $value1) {
                    $data[$key1]['Product']['price'] = number_format($data[$key1]['Product']['price'], 0, ',', '.');
                }
                $this->echoData($data);
            }
        } else {
            $this->echoError();
        }
    }

    // lay sub moi nhat hot nhat cho cac san pham
    public function menu_tab() {
        $params = $this->request->query;
        $page = (isset($params['page'])) ? $params['page'] : 0;
        $conditions = array();
        $order = '';
        if (!empty($params['type']) && (!empty($params['category']) || !empty($params['subcategory']))) {
            $arr1 = array(
                'Product.id',
                'Product.name',
                'Product.title',
                'Product.description',
                'Product.avatar',
                'Product.price',
            );
            if (!empty($params['category'])) {
                $conditions = array(
                    'Product.category_id' => $params['category']
                );
            } else {
                $conditions = array(
                    'Product.subcategory_id' => $params['subcategory']
                );
            }
            switch ($params['type']) {
                case 'new':
                    $order = 'Product.id DESC';
                    break;
                case 'hot':
                    $order = 'Product.user_view DESC';
                    break;
                case 'promotion':
                    $order = 'Product.sale DESC';
                    break;
            }
            $arr = array(
                'conditions' => $conditions,
                'limit' => 10, //int
                'page' => $page, //int
                'order' => $order,
                'fields' => $arr1
            );
            $data = $this->Product->find('all', $arr);
            foreach ($data as $key1 => $value1) {
                $data[$key1]['Product']['price'] = number_format($data[$key1]['Product']['price'], 0, ',', '.');
            }
            $this->echoData($data);
        } else {
            $this->echoError();
        }
    }

    public function promotion() {
        $page = $this->request->query('page');
        $page = (isset($page)) ? $page : 0;
        $arr = array(
            'limit' => 10,
            'order' => 'Promotion.id DESC'
        );
        $data = $this->Promotion->find('all', $arr);
        $data_api = array();
        foreach ($data as $key => $value) {
            $data_api[] = $value['Promotion'];
        }
        $data_api = array(
            'success' => 'true',
            'infor' => $data_api,
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
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
        die;
    }

    function echoError() {
        $data_api = array(
            'success' => 'false',
            'infor' => 'Thiếu tham số',
        );
        $data_api = json_encode($data_api, true);
        echo ($data_api);
        die;
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

    function success($infor) {
        $data_api = array(
            'success' => 'success',
            'infor' => $infor,
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
