<?php

class ApiCusiController extends AppController {

    public $uses = array('User');

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

    //api danh sach don hang
    //api xem gan day
    //api gio hang
    //api thong bao
    //api san pham yeu thich
    //
    
}
