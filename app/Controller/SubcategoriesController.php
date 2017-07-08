<?php

App::uses('AppController', 'Controller');

/**
 * Subcategories Controller
 *
 * @property Subcategory $Subcategory
 * @property PaginatorComponent $Paginator
 */
class SubcategoriesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public function beforeFilter() {
        $this->set('title_for_layout', 'Danh mục con');
        $this->loadModel('Subcategory');
    }

    /**
     * index method
     *
     * @return void
     */
    public function isAuthorized($user) {
        if ($user['role'] != 'admin' && $user['role'] != 'super') {
            throw new NotFoundException('Không có quyền truy cập');
        }
        return parent::isAuthorized($user);
    }

    public function index() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['Subcategory.name LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        if (isset($query['category_id']) && !empty($query['category_id'])) {
            $conditions['Subcategory.category_id'] = $query['category_id'];
        }
        $this->Paginator->settings = array(
            'limit' => ITEMS_PER_PAGE,
            'paramType' => 'querystring',
            'conditions' => $conditions
        );
        $this->Subcategory->recursive = 0;
        $this->set('subcategories', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Subcategory->exists($id)) {
            throw new NotFoundException(__('Invalid subcategory'));
        }
        $options = array('conditions' => array('Subcategory.' . $this->Subcategory->primaryKey => $id));
        $this->set('subcategory', $this->Subcategory->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data_upload = $data['Subcategory'];
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Subcategory']['avatar'];
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
            $this->Subcategory->create();
            if ($this->Subcategory->save($data_upload)) {
                $this->Session->setFlash(__('The subcategory has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The subcategory could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Subcategory->Category->find('list');
        $this->set(compact('categories'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Subcategory->exists($id)) {
            throw new NotFoundException(__('Invalid subcategory'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $data_upload = $data['Subcategory'];
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Subcategory']['avatar'];
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
                if (!empty($img_arr2)) {
                    $data_upload['avatar'] = $img_arr2;
                } else {
                    $data_upload['avatar'] = $data_upload['avatar_tmp'];
                }
            }
            unset($data_upload['avatar_tmp']);
            if ($this->Subcategory->save($data_upload)) {
                $this->Session->setFlash(__('The subcategory has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The subcategory could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Subcategory.' . $this->Subcategory->primaryKey => $id));
            $this->request->data = $this->Subcategory->find('first', $options);
        }
        $categories = $this->Subcategory->Category->find('list');
        $this->set(compact('categories'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Subcategory->id = $id;
        if (!$this->Subcategory->exists()) {
            throw new NotFoundException(__('Invalid subcategory'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Subcategory->delete()) {
            $this->Session->setFlash(__('The subcategory has been deleted.'));
        } else {
            $this->Session->setFlash(__('The subcategory could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function ajaxsub() {
        try {
            $this->autoRender = FALSE;
            $category_id = $this->request->data('category');
            if (!empty($category_id)) {
                $data = $this->Subcategory->find('all', array('conditions' => array('category_id' => $category_id)));
                $rep = '';
                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        $rep = $rep . "<option value='" . $value['Subcategory']['id'] . "'>" . $value['Subcategory']['name'] . "</option>";
                    }
                }
                echo $rep;
                die;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

}
