<?php

class ApiCusiController extends AppController {

    public $uses = array('User', 'Product', 'ViewRecently', 'SocialCount');

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

        $data = $this->ViewRecently->find('all', array(
            'fields' => array('ViewRecently.*', 'Product.*'),
            'conditions' => array(
                'ViewRecently.user_id' => $request['user_id']
            ),
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
        $data = $this->SocialCount->find('all', array(
            'fields' => array('SocialCount.*', 'Product.*'),
            'conditions' => array(
                'SocialCount.user_id' => $request['user_id']
            ),
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
            'order' => array('ViewRecently.id DESC')
        ));
        $this->Baseapi->response('lay thanh cong du lieu', $data);
    }

    //api danh sach don hang
    //api gio hang
    //api thong bao
//
}
