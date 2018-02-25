<?php

App::import('Controller', 'ApiBase');

class ApiCusiController extends ApiBaseController {

    public $uses = array('User', 'Product', 'ViewRecently', 'SocialCount', 'UserBuy', 'Customer', 'Category', 'Subcategory', 'Slide', 'Promotion', 'Employee', 'UserLike', 'Review');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = FALSE;
        $this->Auth->allow('display');
        $this->Auth->allow();
    }

    public $components = array("Baseapi");

    public function get_product($id = 0) {
        $id1 = $this->request->query('id');
        $id = (isset($id1)) ? $id1 : $id;
        if (!$this->Product->exists($id)) {
            $this->response(API_ERROR, 'product_id not exits');
        }
        $user_id = $this->request->query('user_id');
        if (!isset($user_id)) {
            $user_id = 0;
        }
        $data = $this->Product->getDetailProduct($id, $user_id);
        $this->ViewRecently->update_view_recently($user_id, $id);
        $this->response(API_SUCCESS, 'Lấy thành công danh sách', $data);
    }

    //api xem gan day
    public function get_recently_product() {
        $request = $this->request->query;
        if (empty($request['user_id'])) {
            $this->response(API_ERROR, 'missing params: user_id');
        }
        $last_id = !empty($request['last_id']) ? $request['last_id'] : 0;
        $limit = !empty($request['limit']) ? $request['limit'] : 10;
        $conditions = array();
        $conditions['ViewRecently.user_id'] = $request['user_id'];
        if (!empty($last_id)) {
            $conditions['ViewRecently.id <'] = $last_id;
        }
        $data = $this->ViewRecently->find('all', array(
            'fields' => array('Product.*'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'products',
                    'alias' => 'Product',
                    'type' => 'inner',
                    'conditions' => array(
                        'Product.id = ViewRecently.product_id'
                    )
                ),
                array(
                    'table' => 'social_count',
                    'alias' => 'Social',
                    'type' => 'left',
                    'conditions' => array(
                        "Social.product_id = Product.id AND Social.user_id = {$request['user_id']}"
                    )
                )
            ),
            'order' => array('ViewRecently.id DESC'),
            'limit' => $limit
        ));
        $data = $this->add_check_like($data);
        $data = Set::extract('/Product/.', $data);
        $this->response(API_SUCCESS, 'lay thanh cong du lieu', $data);
    }

    //api san pham yeu thich
    public function get_like_product() {
        $request = $this->request->query;
        if (empty($request['user_id'])) {
            $this->response(API_ERROR, 'missing params: user_id');
        }
        $last_id = !empty($request['last_id']) ? $request['last_id'] : 0;
        $limit = !empty($request['limit']) ? $request['limit'] : 10;
        $conditions = array();
        $conditions['SocialCount.user_id'] = $request['user_id'];
        if (!empty($last_id)) {
            $conditions['SocialCount.id <'] = $last_id;
        }
        $data = $this->SocialCount->find('all', array(
            'fields' => array('SocialCount.*', 'Product.*'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'products',
                    'alias' => 'Product',
                    'type' => 'inner',
                    'conditions' => array(
                        'Product.id = SocialCount.product_id'
                    )
                ),
            ),
            'order' => array('SocialCount.id DESC'),
            'limit' => $limit,
        ));
        $this->response(API_SUCCESS, 'lay thanh cong du lieu', $data);
    }

    //kiem tra san phanm da dc mua chua
    public function check_buy() {
        $this->validate_data();
        $user_id = $this->request->data('user_id');
        $product_id = $this->request->data('product_id');
        $data = $this->UserBuy->find('all', array('conditions' => array('UserBuy.customer_id' => $user_id, 'UserBuy.product_id' => $product_id)));
        if ($data) {
            $this->response(API_ERROR, 'Sản phẩm đã được mua');
        } else {
            $this->response(API_SUCCESS, 'Sản phẩm chưa được mua');
        }
    }

    // mua sản phẩm
    public function buy_item() {
        $data = $this->request->data;
        $this->validate_data();
        if (empty($data['product_id'])) {
            $this->response(API_ERROR, 'Missing param: product_id');
        }
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
            if ($this->UserBuy->save($arr)) {
                $this->UserBuy->clear();
                $this->calculate_money($arr['code']);
                $this->response(API_SUCCESS, 'Mua sản phẩm thành công');
            } else {
                $this->response(API_ERROR, 'Mua sản phầm thất bại');
            }
        } else {
            $this->response(API_ERROR, 'Sản phẩm không tồn tại');
        }
    }

    public function calculate_money($user_code) {
        $end_date = date("Y-m") . "-31 00:00:00";
        $start_date = date("Y-m") . "-1 00:00:00";
        $cond = array(
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
                $revenue = $value['UserBuy']['price_sale'] - $value['UserBuy']['partner_price'];
            } elseif ($level == 2) {
                $revenue = $value['UserBuy']['price_sale'] - $value['UserBuy']['employee_price'];
            }
            if (!empty($value['UserBuy']['number_product'])) {
                $revenue = $revenue * $value['UserBuy']['number_product'];
            }
            $value['UserBuy']['revenue'] = $revenue;
            $this->UserBuy->save($value['UserBuy']);
            $this->UserBuy->clear();
        }
    }

    //api delete order buy item
    public function cancel_order() {
        $this->validate_data();
        $data = $this->request->data;
        if (empty($data['product_id'])) {
            $this->response(API_ERROR, 'Missing param: product_id');
        }
        if ($this->Product->exists($data['product_id'])) {
            $this->response(API_ERROR, 'Sản phẩm không tồn tại');
        }
        $conditions = array(
            'UserBuy.customer_id' => $data['user_id'],
            'UserBuy.product_id' => $data['product_id']
        );
        if (!empty($data['date'])) {
            $conditions['UserBuy.date'] = $data['date'];
        }
        $data_buy = $this->UserBuy->find('first', $conditions);
        if (!empty($data_buy['UserBuy']['status']) && ($data_buy['UserBuy']['status'] >= 1)) {

            $this->response(API_ERROR, 'Đơn hàng đã được duyệt không hủy được đơn hàng');
        } else {
            $data_up = $data_buy['UserBuy'];
            $data_up['status'] = 3;
            $this->UserBuy->save($data_up);
            $this->response(API_SUCCESS, 'Hủy thành công đơn hàng');
        }
    }

    //get cancel list
    public function get_cancel_order_list() {
        $this->validate_data();
        $user_id = $this->request->data('user_id');
        $data = $this->UserBuy->find('all', array(
            'conditions' => array(
                'UserBuy.customer_id' => $user_id,
                'UserBuy.status' => CANCEL_BUY,
        )));
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
        $this->response(API_ERROR, 'Lấy danh sách thành công');
    }

    //lay list san phan da mua
    public function get_buy() {
        $this->validate_data();
        $user_id = $this->request->data('user_id');
        $data = $this->UserBuy->find('all', array(
            'conditions' => array(
                'UserBuy.customer_id' => $user_id,
                'UserBuy.status' => SUCCESS_BUY,
        )));
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
                    'sum_price'=>number_format($value['Product']['price']*$value['UserBuy']['number_product'], 0, ',', '.'),
                    'ship'=>0,
                    'total_price'=>number_format($value['Product']['price']*$value['UserBuy']['number_product'], 0, ',', '.'),
                  
                );
                $rep[] = $arr_tmp;
            }
        }
        $this->response(API_SUCCESS, 'Lấy danh sách thành công',$rep);
    }

    //đăng kí user
    public function sign_up() {
        $data = $this->request->data;
        if (empty($data['username']) || empty($data['password']) || empty($data['employee_code']) || empty($data['phone'])) {
            $this->response(API_ERROR, 'Missing params:username, password,employee_code, phone ');
        }
        $check_account = $this->User->checkExitsUser($data['username']);
        if ($check_account) {
            $this->response(API_ERROR, 'Tài khoản đã tồn tại!');
        }
        $employee_code = $this->User->checkExitsCode($data['employee_code']);
        if (!$employee_code) {
            $this->response(API_ERROR, 'Mã nhân viên không tồn tại');
        }
        $address = (isset($data['address'])) ? $data['address'] : '';
        $arr = array(
            'username' => $data['username'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'address' => $address,
            'role' => 'client',
            'employee_code' => !empty($data['employee_code']) ? $data['employee_code'] : 0,
            'created_datetime' => date("Y-m-d H:i:s")
        );
        if ($this->Customer->save($arr)) {
            $this->response(API_SUCCESS, 'Đăng kí thành công!');
        }
        $this->response(API_ERROR, 'Đăng kí thất bại!');
    }

    //đăng nhập vào hệ thống của cusi
    public function login() {
        $request = $this->request->data;
        if (empty($request['username']) || empty($request['password'])) {
            $this->response(API_ERROR, 'Missing param: username, password');
        }
        $data_user = $this->User->find('first', array('conditions' => array('User.username' => $request['username'])));
        if (empty($data_user)) {
            $this->response(API_ERROR, 'Tài khoản không tồn tại');
        }
        if ($data_user['User']['password'] != $request['password']) {
            $this->response(API_ERROR, 'Password sai');
        }
        $token = $this->generateRandomString(16);
        $data_update = array(
            'id' => $data_user['User']['id'],
            'token' => $token
        );
        if ($this->User->save($data_update)) {
            $sale_name_protected = '';
            if (!empty($data_user['User']['sale_id_protected'])) {
                $user_protected = $this->User->find('first', array('conditions' => array('User.code' => $data_user['User']['sale_id_protected'])));
                $sale_name_protected = !empty($user_protected['User']['name']) ? $user_protected['User']['name'] : '';
            }
            $rep = $data_user['User'];
            $rep['token'] = $token;
            $rep['sale_name_protected'] = $sale_name_protected;
            $rep['date_join'] = date("Y-m-d", strtotime($rep['created_datetime']));
            unset($rep['password']);
            $this->response(API_SUCCESS, 'Đăng nhập thành công!', $rep);
        }
    }

    //api edit profile client
    public function edit_profile() {
        $data = $this->request->data;
        $this->validate_data();
        $data_user = $this->Customer->find('first', array('conditions' => array('Customer.id' => $data['user_id'])));
        if ($data_user) {
            $data_change = array();
            $data_change['id'] = $data['user_id'];
            if (!empty($data['password'])) {
                if ($data['password'] != $data_user['Customer']['password']) {
                    if (!empty($data['old_password']) && ($data['old_password'] == $data_user['Customer']['password'])) {
                        $data_change['password'] = $data['password'];
                    } else {
                        $this->response(API_ERROR, 'Mật khẩu hiện tại nhập không đúng!');
                    }
                }
            }

            if (!empty($data['employee_code']) && ($data['employee_code'] != $data_user['Customer']['employee_code'])) {
                $data_change['employee_code'] = $data['employee_code'];
            }
            if (!empty($data['phone']) && ($data['phone'] != $data_user['Customer']['phone'])) {
                $data_change['phone'] = $data['phone'];
            }
            if (isset($data['address']) && ($data['address'] != $data_user['Customer']['address'])) {
                $data_change['address'] = $data['address'];
            }
            if ($this->Customer->save($data_change)) {
                $this->response(API_SUCCESS, 'Thay đổi thông tin thành công !');
            } else {
                $this->response(API_ERROR, 'Thay đổi thông tin thành công !');
            }
        }
    }

    //lấy thông tin user
    public function user_infor() {
        $data = $this->request->data;
        $this->validate_data();
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
        $this->response("Get infor success", $infor_user);
    }

}
