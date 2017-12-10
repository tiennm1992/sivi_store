<?php

App::uses('AppController', 'Controller');

/**
 * Promotions Controller
 *
 * @property Promotion $Promotion
 * @property PaginatorComponent $Paginator
 */
class PromotionsController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    public $uses =array('Category','Product');

    public function beforeFilter() {
        $this->set('title_for_layout', 'Khuyến mại');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        //get category
        $this->Category->unbindModel(array('hasMany' => array('Product','Subcategory')));
        $list_category = $this->Category->find('all', array('fields' => array('Category.id', 'Category.name')));
        //get search
        $query = $this->request->query;
        $conditions = array();
        $conditions['Product.is_sale'] = 1;
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['Product.name LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $category = '';
        if (isset($query['category']) && !empty($query['category'])) {
            $conditions['Product.category_id'] = $query['category'];
            $category = $query['category'];
        }
        $start_time = isset($query['startdate']) ? $query['startdate']  : null;
        $end_time = isset($query['enddate']) ? $query['enddate']: null;
        if ($start_time && $end_time) {
            $conditions[] = "Product.date_create >= date('{$start_time}') AND Product.date_create<= date('{$end_time}')";
        } else if ($end_time) {
            $conditions[] = "Product.date_create <= date('{$end_time}')";
        } else if ($start_time) {
            $conditions[] = "Product.date_create >= date('{$start_time}')";
        }
        $this->Paginator->settings = array(
            'limit' => ITEMS_PER_PAGE,
            'paramType' => 'querystring',
            'conditions' => $conditions,
        );
        $this->Product->recursive = 0;
        $this->set('products', $this->Paginator->paginate("Product"));
        $this->set('name', $name);
        $this->set('category', $category);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('category', $list_category);
    }

    public function old_index() {
        $this->Promotion->recursive = 0;
        $this->set('promotions', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Promotion->exists($id)) {
            throw new NotFoundException(__('Invalid promotion'));
        }
        $options = array('conditions' => array('Promotion.' . $this->Promotion->primaryKey => $id));
        $this->set('promotion', $this->Promotion->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Promotion->create();
            if ($this->Promotion->save($this->request->data)) {
                $this->Session->setFlash(__('The promotion has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The promotion could not be saved. Please, try again.'));
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
        if (!$this->Promotion->exists($id)) {
            throw new NotFoundException(__('Invalid promotion'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Promotion->save($this->request->data)) {
                $this->Session->setFlash(__('The promotion has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The promotion could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Promotion.' . $this->Promotion->primaryKey => $id));
            $this->request->data = $this->Promotion->find('first', $options);
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
        $this->Promotion->id = $id;
        if (!$this->Promotion->exists()) {
            throw new NotFoundException(__('Invalid promotion'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Promotion->delete()) {
            $this->Session->setFlash(__('The promotion has been deleted.'));
        } else {
            $this->Session->setFlash(__('The promotion could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
