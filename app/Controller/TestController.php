<?php

class TestController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'ajax','add');
        $this->layout = FALSE;
//        $this->loadModel('Product');
    }

    public function add() {
         $data = $this->request->data;
         pr($data);die;
        if ($this->request->is('post')) {
            $data = $this->request->data;
            pr($data);
            die;
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

    public function index() {
        $data = $this->request->data;
         pr($data);die;
        if ($this->request->is('post')) {
            die('cdcdc');
            $j = 0; //Variable for indexing uploaded image 

            $target_path = "uploads/"; //Declaring Path for uploaded images
            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {//loop to get individual element from the array
                $validextensions = array("jpeg", "jpg", "png", "PNG");  //Extensions which are allowed
                $ext = explode('.', basename($_FILES['file']['name'][$i])); //explode file name from dot(.) 
                $file_extension = end($ext); //store extensions in the variable

                $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1]; //set the target path with a new name of image
                $j = $j + 1; //increment the number of uploaded images according to the files in array       

                if (($_FILES["file"]["size"][$i] < 100000000) //Approx. 100kb files can be uploaded.
                        && in_array($file_extension, $validextensions)) {
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {//if file moved to uploads folder
                        echo $j . ').<span id="noerror">Image uploaded successfully!.</span><br/><br/>';
                        die;
                    } else {//if file was not moved.
                        echo $j . ').<span id="error">please try again!.</span><br/><br/>';
                        die;
                    }
                } else {//if file size and file type was incorrect.
                    echo $j . ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
                    die;
                }
            }
        }
    }

    public function ajax() {
        $this->autoRender = false;
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $target_path = "uploads/";
            $ext = explode('.', basename($_FILES['file']['name'][$i]));
            $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];

            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
                echo "The file has been uploaded successfully <br />";
            } else {
                echo "There was an error uploading the file, please try again! <br />";
            }
        }
    }

}
