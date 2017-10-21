<?php

App::uses('AppController', 'Controller');

/**
 * Compares Controller
 *
 * @property Compare $Compare
 * @property PaginatorComponent $Paginator
 */
class CompareController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $uses = array('ComparePosition');
    public $components = array('Paginator');

    public function beforeFilter() {
        $this->set('title_for_layout', 'Khuyến mại');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->ComparePosition->recursive = 0;
        $this->set('ComparePosition', $this->Paginator->paginate('ComparePosition'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->ComparePosition->exists($id)) {
            throw new NotFoundException(__('Invalid compare'));
        }
        $options = array('conditions' => array('ComparePosition.' . $this->ComparePosition->primaryKey => $id));
        $this->set('ComparePosition', $this->ComparePosition->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->ComparePosition->create();
            if ($this->ComparePosition->save($this->request->data)) {
                $this->Session->setFlash(__('The compare has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The compare could not be saved. Please, try again.'));
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
        if (!$this->ComparePosition->exists($id)) {
            throw new NotFoundException(__('Invalid ExchangePositions'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->ComparePosition->save($this->request->data)) {
                $this->Session->setFlash(__('The ExchangePositions has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The ExchangePositions could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ComparePosition.' . $this->ComparePosition->primaryKey => $id));
            $this->request->data = $this->ComparePosition->find('first', $options);
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
        $this->ComparePosition->id = $id;
        if (!$this->ComparePosition->exists()) {
            throw new NotFoundException(__('Invalid compare'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->ComparePosition->delete()) {
            $this->Session->setFlash(__('The compare has been deleted.'));
        } else {
            $this->Session->setFlash(__('The compare could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
