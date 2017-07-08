<?php

App::uses('AppController', 'Controller');

/**
 * Customers Controller
 *
 * @property Customer $Customer
 * @property PaginatorComponent $Paginator
 */
class CustomersController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public function beforeFilter() {
        $this->loadModel('Customer');
    }

    /**
     * index method
     *
     * @return void
     */
    public function list_customer() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['Customer.username LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $this->Paginator->settings = array(
            'limit' => ITEMS_PER_PAGE,
            'paramType' => 'querystring',
            'conditions' => $conditions
        );
        $this->Customer->recursive = 0;
        $this->set('customers', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách khách hàng');
    }

    public function index() {
        $user = $this->Auth->user();
        $this->Paginator->settings = array(
            'conditions' => array(
                'Customer.employee_code' => $user['code']
            )
        );
        $user = $this->Auth->user();
        $this->Customer->recursive = 0;
        $this->set('customers', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách khách hàng');
    }

    public function index2($code = 0) {
        $this->Paginator->settings = array(
            'conditions' => array(
                'Customer.employee_code' => $code
            )
        );
        $user = $this->Auth->user();
        $this->Customer->recursive = 0;
        $this->set('customers', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách khách hàng');
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Customer->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }
        $options = array('conditions' => array('Customer.' . $this->Customer->primaryKey => $id));
        $this->set('customer', $this->Customer->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Customer->create();
            if ($this->Customer->save($this->request->data)) {
                $this->Session->setFlash(__('The customer has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
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
//        pr($this->Auth->user('name'));die;
        if (!$this->Customer->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Customer->save($this->request->data)) {
                $this->Session->setFlash(__('The customer has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Customer.' . $this->Customer->primaryKey => $id));
            $this->request->data = $this->Customer->find('first', $options);
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
        $this->Customer->id = $id;
        if (!$this->Customer->exists()) {
            throw new NotFoundException(__('Invalid customer'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Customer->delete()) {
            $this->Session->setFlash(__('The customer has been deleted.'));
        } else {
            $this->Session->setFlash(__('The customer could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
