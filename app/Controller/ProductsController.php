<?php

App::uses('AppController', 'Controller');

/**
 * Products Controller
 *
 * @property Product $Product
 * @property PaginatorComponent $Paginator
 */
class ProductsController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Sản phẩm');
        $this->loadModel('Product');
        $this->loadModel('Category');
    }

    /**
     * index method
     *
     * @return void
     */
    public function isAuthorized($user) {
        if ($user['role'] == 'admin') {
            $this->redirect('/userBuys/order');
        }
        if ($user['role'] != 'super') {
            $this->redirect('/customers/index');
            throw new NotFoundException('Không có quyền truy cập');
        }
        return parent::isAuthorized($user);
    }

    public function index() {
        //get category
        $this->Category->unbindModel(array('hasMany' => array('Product','Subcategory')));
        $list_category = $this->Category->find('all', array('fields' => array('Category.id', 'Category.name')));
        //get search
        $query = $this->request->query;
        $conditions = array();
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
        $this->set('products', $this->Paginator->paginate());
        $this->set('name', $name);
        $this->set('category', $category);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('category', $list_category);
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Product->exists($id)) {
            throw new NotFoundException(__('Invalid product'));
        }
        $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
        $this->set('product', $this->Product->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add1() {
        if ($this->request->is('post')) {
            $this->Product->create();
            if ($this->Product->save($this->request->data)) {
                $this->Session->setFlash(__('The product has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Product->Category->find('list');
        $subcategories = $this->Product->Subcategory->find('list');
        $this->set(compact('categories', 'subcategories'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data_upload = $data['Product'];
            $img_arr = '';
            $file = $data['Product']['img'];
            if (!empty($file)) {
                for ($i = 0; $i < count($file); $i++) {//loop to get individual element from the array
                    $target_path = "uploads/"; //Declaring Path for uploaded images
                    $validextensions = array("jpeg", "jpg", "png", "PNG");  //Extensions which are allowed
                    $ext = explode('.', basename($file[$i]['name'])); //explode file name from dot(.) 
                    $file_extension = end($ext); //store extensions in the variable

                    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1]; //set the target path with a new name of image

                    if (($file[$i]['size'] < 100000000) //Approx. 100kb files can be uploaded.
                            && in_array($file_extension, $validextensions)) {
                        if (move_uploaded_file($file[$i]['tmp_name'], $target_path)) {//if file moved to uploads folder
                            $img_arr = $img_arr . "|" . $target_path;
                        } else {//if file was not moved.
                            echo ').<span id="error">please try again!.</span><br/><br/>';
                            die;
                        }
                    } else {//if file size and file type was incorrect.
                        break;
                    }
                }
            }
            $data_upload['img'] = $img_arr;
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Product']['avatar'];
            if (!empty($avatar)) {
                $target_path = "uploads/"; //Declaring Path for uploaded images
                $validextensions = array("jpeg", "jpg", "png", "PNG");  //Extensions which are allowed
                $ext = explode('.', basename($avatar['name'])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1]; //set the target path with a new name of image

                if (($avatar['size'] < 100000000) //Approx. 100kb files can be uploaded.
                        && in_array($file_extension, $validextensions)) {
                    if (move_uploaded_file($avatar['tmp_name'], $target_path)) {//if file moved to uploads folder
                        $img_arr2 = $target_path;
                    } else {//if file was not moved.
                        echo ').<span id="error">please try again!.</span><br/><br/>';
                        die;
                    }
                }
            }
            $data_upload['avatar'] = $img_arr2;
            //time
            $data_upload['date_create'] = $data_upload['date_create']['year']
                    . '-' . $data_upload['date_create']['month']
                    . '-' . $data_upload['date_create']['day']
                    . ' ' . "00:00:00";
//            pr($data_upload);die;
            $this->Product->create();
            if ($this->Product->save($data_upload)) {
                $this->Session->setFlash(__('The product has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Product->Category->find('list');
        $subcategories = $this->Product->Subcategory->find('list');
        $this->set(compact('categories', 'subcategories'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Product->exists($id)) {
            throw new NotFoundException(__('Invalid product'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $data_upload = $data['Product'];
           
            $img_arr = '';
            $file = $data['Product']['img'];
            if (!empty($data['Product']['img_tmp']) && isset($data['Product']['img_tmp'])) {
                $file_tmp = $data['Product']['img_tmp'];
                foreach ($file_tmp as $key => $value) {
                    $img_arr = $img_arr . "|" . $value;
                }
            }
            if (!empty($file)) {
                for ($i = 0; $i < count($file); $i++) {//loop to get individual element from the array
                    $target_path = "uploads/"; //Declaring Path for uploaded images
                    $validextensions = array("jpeg", "jpg", "png", "PNG");  //Extensions which are allowed
                    $ext = explode('.', basename($file[$i]['name'])); //explode file name from dot(.) 
                    $file_extension = end($ext); //store extensions in the variable

                    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1]; //set the target path with a new name of image

                    if (($file[$i]['size'] < 100000000) //Approx. 100kb files can be uploaded.
                            && in_array($file_extension, $validextensions)) {
                        if (move_uploaded_file($file[$i]['tmp_name'], $target_path)) {//if file moved to uploads folder
                            $img_arr = $img_arr . "|" . $target_path;
                        } else {//if file was not moved.
                            echo ').<span id="error">please try again!.</span><br/><br/>';
                            die;
                        }
                    } else {//if file size and file type was incorrect.
                        break;
                    }
                }
            }
            $data_upload['img'] = $img_arr;
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Product']['avatar'];
            if (!empty($avatar) && isset($avatar)) {
                $target_path = "uploads/"; //Declaring Path for uploaded images
                $validextensions = array("jpeg", "jpg", "png", "PNG");  //Extensions which are allowed
                $ext = explode('.', basename($avatar['name'])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1]; //set the target path with a new name of image

                if (($avatar['size'] < 100000000) //Approx. 100kb files can be uploaded.
                        && in_array($file_extension, $validextensions)) {
                    if (move_uploaded_file($avatar['tmp_name'], $target_path)) {//if file moved to uploads folder
                        $img_arr2 = $target_path;
                    } else {//if file was not moved.
                        echo ').<span id="error">please try again!.</span><br/><br/>';
                        die;
                    }
                }
                if (!empty($img_arr2)) {
                    $data_upload['avatar'] = $img_arr2;
                } else {
                    $data_upload['avatar'] = $data_upload['avatar_tmp'];
                }
            }
            //time
            $data_upload['date_create'] = $data_upload['date_create']['year']
                    . '-' . $data_upload['date_create']['month']
                    . '-' . $data_upload['date_create']['day']
                    . ' ' . "00:00:00";
//            pr($data_upload);die;
            unset($data_upload['avatar_tmp']);
            unset($data_upload['img_tmp']);
            $this->Product->create();
            if ($this->Product->save($data_upload)) {
                $this->Session->setFlash(__('The product has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
            if ($this->Product->save($this->request->data)) {
                $this->Session->setFlash(__('The product has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
            $this->request->data = $this->Product->find('first', $options);
        }
        $categories = $this->Product->Category->find('list');
        $subcategories = $this->Product->Subcategory->find('list');
        $this->set(compact('categories', 'subcategories'));
    }

    public function edit_origin($id = null) {
        if (!$this->Product->exists($id)) {
            throw new NotFoundException(__('Invalid product'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Product->save($this->request->data)) {
                $this->Session->setFlash(__('The product has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
            $this->request->data = $this->Product->find('first', $options);
        }
        $categories = $this->Product->Category->find('list');
        $subcategories = $this->Product->Subcategory->find('list');
        $this->set(compact('categories', 'subcategories'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Product->id = $id;
        if (!$this->Product->exists()) {
            throw new NotFoundException(__('Invalid product'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Product->delete()) {
            $this->Session->setFlash(__('The product has been deleted.'));
        } else {
            $this->Session->setFlash(__('The product could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
