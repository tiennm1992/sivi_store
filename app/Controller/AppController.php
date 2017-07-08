<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    protected $instagram;
    protected $apiCallBack = '';
    public $layout = "adminlte";
    protected $user_current;
//    var $components = array('Session', 'Cookie', 'Auth', 'RequestHandler');
    public $user = array('Users');
    public $helpers = array('Session', 'Form' => array('className' => 'CkForm'), 'Html', 'Grid');
    public $components = array(
        'Session', 'Cookie', 'RequestHandler',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'products',
                'action' => 'index'
            ),
            'authError' => 'You must be logged in to view this page!!!',
            'logoutRedirect' => array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'User',
                    'passwordHasher' => array(
                        'className' => 'Simple',
                        'hashType' => 'md5'
                    ),
                )
            )
//            'authenticate' => array(
//                'Form' => array(
//                    'passwordHasher' => 'md5'
////                    'passwordHasher' => 'Blowfish'
//                )
//            )
        )
    );

    //de check phan quyen cho con troller
    public function beforeFilter() {
        // Controller autorization is the simplest form.
        $this->Auth->authorize = 'Controller';
//        $this->Auth->authorize = array('Controller');
    }

    //de phaan quyen
    public function isAuthorized($user) {
        if (
                isset($user['role']) &&
                (
                $user['role'] == 'admin' ||
                $user['role'] == 'employee' ||
                $user['role'] == 'super'
                )
        ) {

            return true;
        }
        return false;
    }

    public function beforeRender() {
        $user = $this->Auth->user();
        $this->set('user', $user);
    }

//    public function beforeFilter() {
//        Security::setHash("md5");
//        $this->Auth->userModel = 'User';
//        $this->Auth->fields = array('username' => 'username', 'password' => 'password');
//        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
//        $this->Auth->loginRedirect = array('controller' => 'products', 'action' => 'index');
//        $this->Auth->loginError = 'Username / password combination.  Please try again';
//        $this->Auth->authorize = 'controller';
////        $this->Auth->allow('index', 'view');
////        $this->set("admin", $this->_isAdmin());
////        $this->set("logged_in", $this->_isLogin());
////        $this->set("users_userid", $this->_usersUserID());
////        $this->set("users_username", $this->_usersUsername());
//    }
//    function isAuthorized() {
//        return true;
//    }
}
