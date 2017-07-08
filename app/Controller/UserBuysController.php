<?php

App::uses('AppController', 'Controller');

/**
 * UserBuys Controller
 *
 * @property UserBuy $UserBuy
 * @property PaginatorComponent $Paginator
 */
class UserBuysController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function beforeFilter() {
        $this->loadModel('UserBuy');
        $this->loadModel('Customer');
    }

    public function index() {
        $user = $this->Auth->user();
        $conditions = array(
            'UserBuy.code' => $user['code']
        );
        $this->UserBuy->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => $conditions,
        );

        //tinh tien cua thang do
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $cond = array(
            'UserBuy.status' => 2,
            'UserBuy.code' => $user['code'],
            "UserBuy.date <= '{$end_date}'",
            "UserBuy.date > '{$start_date}'",
        );
        $sum_revenue = $this->UserBuy->find('all', array(
            'conditions' => $cond,
            'fields' => array('sum(UserBuy.revenue*UserBuy.number_product) as total_sum', 'sum(UserBuy.price_sale*UserBuy.number_product) as total_price', 'sum(UserBuy.number_product) as total_product',),
        ));
        $total_revenue = $sum_revenue[0][0]['total_sum'];
        $total_price = $sum_revenue[0][0]['total_price'];
        $total_product = $sum_revenue[0][0]['total_product'];
        $point = $total_revenue / 1000;
        $level = 0;
        if ($total_product > 2 && $total_product < 10) {
            $level = 1;
        }
        if ($total_product > 9) {
            $level = 2;
        }
        $this->set('sum', $total_revenue);
        $this->set('total_price', $total_price);
        $this->set('total_product', $total_product);
        $this->set('point', $point);
        $this->set('level', $level);
        $this->set('userBuys', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Thống kê mua hàng');
    }

    public function list_revenu() {
        $user = $this->Auth->user();
        $conditions = array(
            'UserBuy.code' => $user['code']
        );
        $this->UserBuy->recursive = 0;
        //tinh tien cua thang do
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $cond = array(
            'UserBuy.status' => 2,
            'UserBuy.code' => $user['code'],
            "UserBuy.date <= '{$end_date}'",
            "UserBuy.date > '{$start_date}'",
        );
        $sum_revenue = $this->UserBuy->find('all', array(
            'conditions' => $cond,
            'fields' => array('sum(UserBuy.revenue*UserBuy.number_product) as total_sum', 'month' => 'MONTH(date)'),
            'group' => array('MONTH(date)')
        ));
        pr($sum_revenue);
        die;
        $sum_revenue = $sum_revenue[0][0]['total_sum'];
        $this->set('sum', $sum_revenue);
        $this->set('title_for_layout', 'Thống kê mua hàng');
    }

    public function index2($code = 0) {
//        $user = $this->Auth->user();
        $conditions = array(
            'UserBuy.code' => $code
        );
        $this->UserBuy->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => $conditions,
        );

        //tinh tien cua thang do
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $cond = array(
            'UserBuy.code' => $code,
            "UserBuy.date <= '{$end_date}'",
            "UserBuy.date > '{$start_date}'",
        );
        $sum_revenue = $this->UserBuy->find('all', array(
            'conditions' => $cond,
            'fields' => array('sum(UserBuy.revenue*UserBuy.number_product) as total_sum', 'sum(UserBuy.price_sale*UserBuy.number_product) as total_price', 'sum(UserBuy.number_product) as total_product',),
        ));
        $total_revenue = $sum_revenue[0][0]['total_sum'];
        $total_price = $sum_revenue[0][0]['total_price'];
        $total_product = $sum_revenue[0][0]['total_product'];
        $point = $total_revenue / 1000;
        $level = 0;
        if ($total_product > 2 && $total_product < 10) {
            $level = 1;
        }
        if ($total_product > 9) {
            $level = 2;
        }
        $this->set('sum', $total_revenue);
        $this->set('total_price', $total_price);
        $this->set('total_product', $total_product);
        $this->set('point', $point);
        $this->set('level', $level);
        $this->set('userBuys', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Thống kê mua hàng');
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->UserBuy->exists($id)) {
            throw new NotFoundException(__('Invalid user buy'));
        }
        $options = array('conditions' => array('UserBuy.' . $this->UserBuy->primaryKey => $id));
        $this->set('userBuy', $this->UserBuy->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->UserBuy->create();
            if ($this->UserBuy->save($this->request->data)) {
                $this->Session->setFlash(__('The user buy has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user buy could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->UserBuy->exists($id)) {
            throw new NotFoundException(__('Invalid user buy'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->UserBuy->save($this->request->data)) {
                $this->Session->setFlash(__('The user buy has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user buy could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('UserBuy.' . $this->UserBuy->primaryKey => $id));
            $this->request->data = $this->UserBuy->find('first', $options);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->UserBuy->id = $id;
        if (!$this->UserBuy->exists()) {
            throw new NotFoundException(__('Invalid user buy'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->UserBuy->delete()) {
            $this->Session->setFlash(__('The user buy has been deleted.'));
        } else {
            $this->Session->setFlash(__('The user buy could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'order'));
    }

    public function delete_order_success($id = null) {
        try {
            $this->UserBuy->id = $id;
            if (!$this->UserBuy->exists()) {
                throw new NotFoundException(__('Invalid user buy'));
            }
            $this->request->allowMethod('post', 'delete');
            $this->UserBuy->recursive = -1;
            if ($this->UserBuy->delete()) {
                $this->Session->setFlash(__('The user buy has been deleted.'));
            } else {
                $this->Session->setFlash(__('The user buy could not be deleted. Please, try again.'));
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        return $this->redirect(array('action' => 'success_buy'));
    }

    public function order() {
        try {
            $conditions = array(
                'UserBuy.status' => 0,
            );
            $this->UserBuy->recursive = 0;
            $this->Paginator->settings = array(
                'conditions' => $conditions,
                'limit' => 15,
                'order' => array(
                    'UserBuy.id' => 'DESC'
                ),
            );
            $this->set('userBuys', $this->Paginator->paginate());
            $this->set('title_for_layout', 'Danh sách đơn hàng mới');
        } catch (Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

    public function approval($buy_id = 0) {
//        $this->UserBuy->id->$buy_id;
        $arr = array(
            'id' => $buy_id,
            'status' => 1,
        );
//        $this->UserBuy->save($arr);
        if ($this->UserBuy->save($arr)) {
            $this->redirect('/userBuys/order');
        }
    }

    public function approval_success($buy_id = 0) {
//        $this->UserBuy->id->$buy_id;
        $arr = array(
            'id' => $buy_id,
            'status' => 2,
        );
//        $this->UserBuy->save($arr);
        if ($this->UserBuy->save($arr)) {
            $this->redirect('/userBuys/check_buy');
        }
    }

    public function check_buy() {
        try {
            $conditions = array(
                'UserBuy.status' => 1,
            );
            $this->UserBuy->recursive = 0;
            $this->Paginator->settings = array(
                'conditions' => $conditions,
                'limit' => 15,
                'order' => array(
                    'UserBuy.id' => 'DESC'
                ),
            );
            $this->set('userBuys', $this->Paginator->paginate());
            $this->set('title_for_layout', 'Danh sách đơn hàng đang chờ');
        } catch (Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

    public function success_buy() {
        try {
            $conditions = array(
                'UserBuy.status' => 2,
            );
            $this->UserBuy->recursive = 0;
            $this->Paginator->settings = array(
                'conditions' => $conditions,
                'limit' => 15,
                'order' => array(
                    'UserBuy.id' => 'DESC'
                ),
            );
//            pr($this->Paginator->paginate());die;
            $this->set('userBuys', $this->Paginator->paginate());
            $this->set('title_for_layout', 'Danh đơn hàng thành công');
        } catch (Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

}
