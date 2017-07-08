<?php

App::uses('AppController', 'Controller');

/**
 * Employees Controller
 *
 * @property Employee $Employee
 * @property PaginatorComponent $Paginator
 */
class EmployeesController extends AppController {

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
    public function index() {
        $this->Employee->recursive = 0;
        $this->set('employees', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Employee->exists($id)) {
            throw new NotFoundException(__('Invalid employee'));
        }
        $options = array('conditions' => array('Employee.' . $this->Employee->primaryKey => $id));
        $this->set('employee', $this->Employee->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Employee->create();
            if ($this->Employee->save($this->request->data)) {
                $this->Session->setFlash(__('The employee has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The employee could not be saved. Please, try again.'));
            }
        }
    }

    public function ajaxcode() {
        $this->autoRender = FALSE;
        while (true) {
            $code = $this->generateRandomString();
            $check = $this->Employee->checkExitsCode($code);
            if (!$check) {
                return $code;
                die;
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
        if (!$this->Employee->exists($id)) {
            throw new NotFoundException(__('Invalid employee'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Employee->save($this->request->data)) {
                $this->Session->setFlash(__('The employee has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The employee could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Employee.' . $this->Employee->primaryKey => $id));
            $this->request->data = $this->Employee->find('first', $options);
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
        $this->Employee->id = $id;
        if (!$this->Employee->exists()) {
            throw new NotFoundException(__('Invalid employee'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Employee->delete()) {
            $this->Session->setFlash(__('The employee has been deleted.'));
        } else {
            $this->Session->setFlash(__('The employee could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
