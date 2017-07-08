<?php

App::uses('AppController', 'Controller');

/**
 * Slides Controller
 *
 * @property Slide $Slide
 * @property PaginatorComponent $Paginator
 */
class SlidesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    public function beforeFilter() {
        $this->set('title_for_layout', 'Slide');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Slide->recursive = 0;
        $this->set('slides', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $options = array('conditions' => array('Slide.' . $this->Slide->primaryKey => $id));
        $this->set('slide', $this->Slide->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data_upload = $data['Slide'];
            //upload avatar
            $img_arr2 = '';
            $avatar = $data['Slide']['image'];
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
            $data_upload['image'] = $img_arr2;
           
            $this->Slide->create();
            if ($this->Slide->save($data_upload)) {
                $this->Session->setFlash(__('The slide has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The slide could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null) {
        if (!$this->Slide->exists($id)) {
            throw new NotFoundException(__('Invalid slide'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Slide->save($this->request->data)) {
                $this->Session->setFlash(__('The slide has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The slide could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Slide.' . $this->Slide->primaryKey => $id));
            $this->request->data = $this->Slide->find('first', $options);
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
        $this->Slide->id = $id;
        if (!$this->Slide->exists()) {
            throw new NotFoundException(__('Invalid slide'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Slide->delete()) {
            $this->Session->setFlash(__('The slide has been deleted.'));
        } else {
            $this->Session->setFlash(__('The slide could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
