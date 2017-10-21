<?php
App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'infor');
        $this->loadModel('Customer');
        $this->loadModel('User');
        $this->loadModel('User');
        $this->loadModel('UserPosition');
        $this->loadModel('UserBuy');
        $this->loadModel('UserLevel');
    }

    public function isAuthorized($user) {
        $action = $this->request->params['action'];
        if ($user['role'] != 'super' && $action != 'edit_sasi') {
            throw new NotFoundException('Không có quyền truy cập');
        }
        return parent::isAuthorized($user);
    }

    public function infor() {
        $user = $this->Auth->user();
        $this->render(false);
    }

    public function login() {
        $this->layout = 'alogin';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Session->setFlash(__('ok'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->findById($id));
    }

    public function addajax() {
        try {
            $this->autoRender = FALSE;
            if ($this->request->is('post')) {
                $data = $this->request->data;
                $data = $data['User'];
                if (strpos($data['username'], ' ') !== false) {
                    echo 'Tài khoản không được chứa khoảng trắng';
                    die;
                }
                if ($this->User->checkExitsUser($data['username'])) {
                    echo 'Tài khoản đã tồn tại';
                    die;
                }
                if ($this->Customer->find('all', array('conditions' => array('Customer.username' => $data['username'])))) {
                    echo 'Tài khoản đã tồn tại';
                    die;
                }

                $save_date = $this->request->data;
                $save_date = $save_date['User'];
                if (empty($save_date['sale_id_protected'])) {
                    unset($save_date['sale_id_protected']);
                }
                //$this->User->create();
                if ($this->User->save($save_date)) {
                    if (!empty($save_date['code'])) {
                        $this->UserLevel->update_level($save_date['code']);
                    }
                    if (!empty($save_date['sale_id_protected'])) {
                        $this->UserLevel->update_level($save_date['sale_id_protected']);
                    }
                    echo 'done';
                    die;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            $data = $this->request->data;
            if ($this->User->save($this->request->data)) {
                if (!empty($data['code'])) {
                    $this->UserLevel->update_level($data['code']);
                }
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }

    public function edit($id = null, $action = 'index') {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $data = $data['User'];
            $id = $data['id'];
            $check_user = $this->User->find('all', array('conditions' => array('User.username' => $data['username'], "User.id!=$id")));
            if (!$check_user) {
                $save_data=$this->request->data;
                unset($save_data['password']);
                if ($this->User->save($save_data, array('validate' => false, 'callbacks' => false))) {
                    $this->Session->setFlash(__('The User has been saved.'));
                    return $this->redirect(array('action' => $action));
                } else {
                    $this->Session->setFlash(__('The User could not be saved. Please, try again.'));
                }
            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $this->request->data = $this->User->find('first', $options);
        }
    }

    public function edit_sasi() {
        $infor = $this->Auth->user();
        $id = $infor['id'];
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $data = $data['User'];
            $id = $data['id'];
            $check_user = $this->User->find('all', array('conditions' => array('User.username' => $data['username'], "User.id!=$id")));
            if (!$check_user) {
                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__('The User has been saved.'));
                    return $this->redirect('/sasi/user_infor');
                } else {
                    $this->Session->setFlash(__('The User could not be saved. Please, try again.'));
                }
            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $this->request->data = $this->User->find('first', $options);
        }
    }

    public function delete($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid User'));
        }
        $this->request->allowMethod('post', 'delete');
        $user_data = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            )
        ));
        if ($this->User->delete()) {
            $this->UserPosition->deleteAll(array('user_id' => $id));
            $this->UserBuy->deleteAll(array('UserBuy.code' => $user_data['User']['code']));
            $this->Session->setFlash(__('The User has been deleted.'));
        } else {
            $this->Session->setFlash(__('The User could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'employee'));
    }

    public function ajaxcode() {
        $this->autoRender = FALSE;
        while (true) {
            $code = $this->generateRandomString();
            $check = $this->User->checkExitsCode($code);
            if (!$check) {
                return $code;
                die;
            }
        }
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

    public function employee() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['User.username LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $conditions['User.role'] = 'employee';
        //$conditions['OR']=array(
        //  'User.role'=>'employee',
        //   'User.role'=>'partner',
        //);
        $this->Paginator->settings = array(
            'conditions' =>
            $conditions
        );
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách nhân viên kinh doanh');
    }

    public function partner() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['User.username LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $conditions['User.role'] = 'partner';
        $this->Paginator->settings = array(
            'conditions' => $conditions
        );
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách cộng tác viên');
    }

    public function admin() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['User.username LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        }
        $conditions['User.role'] = 'admin';
        $this->Paginator->settings = array(
            'conditions' => $conditions
        );
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách nhân viên chăm sóc khách hàng');
    }

    public function super() {
        $query = $this->request->query;
        $conditions = array();
        $name = '';
        if (isset($query['name']) && !empty($query['name'])) {
            $conditions['User.username LIKE'] = "%{$query['name']}%";
            $name = $query['name'];
        };
        $conditions['User.role'] = 'super';
        $this->Paginator->settings = array(
            'conditions' => $conditions
        );
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
        $this->set('title_for_layout', 'Danh sách tài khoản người quản trị');
    }

}
