<?php

class ApiCusiController extends AppController {

    public $uses = array('User', 'Product', 'ViewRecently', 'SocialCount', 'UserBuy', 'Customer', 'Category', 'Subcategory', 'Slide', 'Promotion', 'Employee', 'UserLike', 'Review');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = FALSE;
        $this->Auth->allow('display');
        $this->Auth->allow();
    }

    public $components = array("Baseapi");

    public function test_api() {
        $this->Baseapi->response('helllo', 'apc');
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
        $this->Baseapi->response('Lấy thành công danh sách', $rep);
    }

    public function get_product($id = 0) {
        $id1 = $this->request->query('id');
        $id = (isset($id1)) ? $id1 : $id;
        if (!$this->Product->exists($id)) {
            $this->Baseapi->response('product_id not exits', array(), API_ERROR);
        }
        $user_id = $this->request->query('user_id');
        if (!isset($user_id)) {
            $user_id = 0;
        }
        $data = $this->Product->getDetailProduct($id, $user_id);
        $this->ViewRecently->update_view_recently($user_id, $id);
        $this->Baseapi->response('Lấy thành công danh sách', $data);
    }

    //api xem gan day
    public function get_recently_product() {
        $request = $this->request->query;
        if (empty($request['user_id'])) {
            $this->Baseapi->response('missing params: user_id', array(), API_ERROR);
        }
        $last_id = !empty($request['last_id']) ? $request['last_id'] : 0;
        $limit = !empty($request['limit']) ? $request['limit'] : 10;
        $conditions = array();
        $conditions['ViewRecently.user_id'] = $request['user_id'];
        if (!empty($last_id)) {
            $conditions['ViewRecently.id <'] = $last_id;
        }
        $data = $this->ViewRecently->find('all', array(
            'fields' => array('ViewRecently.*', 'Product.*'),
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
            'order' => array('ViewRecently.id DESC')
        ));
        $data = $this->Baseapi->add_check_like($data);
        $this->Baseapi->response('lay thanh cong du lieu', $data);
    }

    //api san pham yeu thich
    public function get_like_product() {
        $request = $this->request->query;
        if (empty($request['user_id'])) {
            $this->Baseapi->response('missing params: user_id', array(), API_ERROR);
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
        $this->Baseapi->response('lay thanh cong du lieu', $data);
    }

    //api thong bao
    public function get_notification_list() {
        
    }

    //kiem tra san phanm da dc mua chua
    public function check_buy() {
        $this->baseapi->validate_data();
        $user_id = $this->request->data('user_id');
        $product_id = $this->request->data('product_id');
        $data = $this->UserBuy->find('all', array('conditions' => array('UserBuy.customer_id' => $user_id, 'UserBuy.product_id' => $product_id)));
        if ($data) {
            $this->Baseapi->response('Sản phẩm đã được mua', array(), API_ERROR);
        } else {
            $this->Baseapi->response('Sản phẩm chưa được mua', array(), API_SUCCESS);
        }
    }

    // mua sản phẩm
    public function buy_item() {
        $data = $this->request->data;
        $this->baseapi->validate_data();
        if (empty($data['product_id'])) {
            $this->baseapi->response('Missing param: product_id', array(), API_ERROR);
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
                $this->Baseapi->response('Mua sản phẩm thành công', array(), API_SUCCESS);
            } else {
                $this->Baseapi->response('Mua sản phầm thất bại', array(), API_ERROR);
            }
        } else {
            $this->Baseapi->response('Sản phẩm không tồn tại', array(), API_ERROR);
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
        $this->baseapi->validate_data();
        $data = $this->request->data;
        if (empty($data['product_id'])) {
            $this->baseapi->response('Missing param: product_id', array(), API_ERROR);
        }
        if ($this->Product->exists($data['product_id'])) {
            $this->Baseapi->response('Sản phẩm không tồn tại', array(), API_ERROR);
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

            $this->Baseapi->response('Đơn hàng đã được duyệt không hủy được đơn hàng', array(), API_ERROR);
        } else {
            $data_up = $data_buy['UserBuy'];
            $data_up['status'] = 3;
            $this->UserBuy->save($data_up);
            $this->Baseapi->response('Hủy thành công đơn hàng', array(), API_SUCCESS);
        }
    }

    //get cancel list
    public function get_cancel_order_list() {
        $this->baseapi->validate_data();
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
        $this->Baseapi->response('Lấy danh sách thành công', $rep, API_ERROR);
    }

    //lay list san phan da mua
    public function get_buy() {
        $this->baseapi->validate_data();
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
                );
                $rep[] = $arr_tmp;
            }
        }
        $this->Baseapi->response('Lấy danh sách thành công', $rep, API_ERROR);
    }

}
