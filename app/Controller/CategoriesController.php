<?php

App::uses('AppController', 'Controller');

/**
 * Categories Controller
 *
 * @property Category $Category
 * @property PaginatorComponent $Paginator
 */
class CategoriesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public function beforeFilter() {
        $this->set('title_for_layout', 'Danh mục');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['Category.name LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $this->Paginator->settings = array(
            'limit' => ITEMS_PER_PAGE,
            'paramType' => 'querystring',
            'conditions' => $conditions
        );
        $this->Category->recursive = 0;
        $this->set('categories', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Category->exists($id)) {
            throw new NotFoundException(__('Invalid category'));
        }
        $options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
        $this->set('category', $this->Category->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add1() {
        if ($this->request->is('post')) {
            $this->Category->create();
            if ($this->Category->save($this->request->data)) {
                $this->Session->setFlash(__('The category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The category could not be saved. Please, try again.'));
            }
        }
    }

    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data_upload = $data['Category'];
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Category']['avatar'];
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
            $this->Category->create();
            if ($this->Category->save($data_upload)) {
                $this->Session->setFlash(__('The category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The category could not be saved. Please, try again.'));
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
        if (!$this->Category->exists($id)) {
            throw new NotFoundException(__('Invalid category'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $data_upload = $data['Category'];
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Category']['avatar'];
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
            $this->Category->create();
            if ($this->Category->save($data_upload)) {
                $this->Session->setFlash(__('The category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The category could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
            $this->request->data = $this->Category->find('first', $options);
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
        $this->Category->id = $id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__('Invalid category'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Category->delete()) {
            $this->Session->setFlash(__('The category has been deleted.'));
        } else {
            $this->Session->setFlash(__('The category could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user) {
        if ($user['role'] != 'admin' && $user['role'] != 'super') {
            throw new NotFoundException('Không có quyền truy cập');
        }
        return parent::isAuthorized($user);
    }

}
