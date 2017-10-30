<?php

App::uses('AppController', 'Controller');

/**
 * ExchangePositions Controller
 *
 * @property ExchangePositions $ExchangePositions
 * @property PaginatorComponent $Paginator
 */
class ExchangeController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $uses = array('ExchangePositions');
    public $components = array('Paginator');

    public function beforeFilter() {
        $this->set('title_for_layout', 'Bảng qui đổi');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->ExchangePositions->recursive = 0;
        $this->set('ExchangePositions', $this->Paginator->paginate('ExchangePositions'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->ExchangePositions->exists($id)) {
            throw new NotFoundException(__('Invalid ExchangePositions'));
        }
        $options = array('conditions' => array('ExchangePositions.' . $this->ExchangePositions->primaryKey => $id));
        $this->set('ExchangePositions', $this->ExchangePositions->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->ExchangePositions->create();
            if ($this->ExchangePositions->save($this->request->data)) {
                $this->Session->setFlash(__('The ExchangePositions has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The ExchangePositions could not be saved. Please, try again.'));
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
        if (!$this->ExchangePositions->exists($id)) {
            throw new NotFoundException(__('Invalid ExchangePositions'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->ExchangePositions->save($this->request->data)) {
                $this->Session->setFlash(__('The ExchangePositions has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The ExchangePositions could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ExchangePositions.' . $this->ExchangePositions->primaryKey => $id));
            $this->request->data = $this->ExchangePositions->find('first', $options);
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
        $this->ExchangePositions->id = $id;
        if (!$this->ExchangePositions->exists()) {
            throw new NotFoundException(__('Invalid ExchangePositions'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->ExchangePositions->delete()) {
            $this->Session->setFlash(__('The ExchangePositions has been deleted.'));
        } else {
            $this->Session->setFlash(__('The ExchangePositions could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
