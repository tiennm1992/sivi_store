<?php

App::uses('AppModel', 'Model');

/**
 * Subcategory Model
 *
 * @property Category $Category
 * @property Product $Product
 */
class Subcategory extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'category_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'sort' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
//        'avatar' => array(
//            // http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::uploadError
//            'uploadError' => array(
//                'rule' => 'uploadError',
//                'message' => 'Something went wrong with the file upload',
//                'required' => FALSE,
//                'allowEmpty' => TRUE,
//            ),
//            // http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::mimeType
//            'mimeType' => array(
//                'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
//                'message' => 'Invalid file, only images allowed',
//                'required' => FALSE,
//                'allowEmpty' => TRUE,
//            ),
//            // custom callback to deal with the file upload
//            'processUpload' => array(
//                'rule' => 'processUpload',
//                'message' => 'Something went wrong processing your file',
//                'required' => FALSE,
//                'allowEmpty' => TRUE,
//                'last' => TRUE,
//            )
//        )
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'subcategory_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $uploadDir = 'uploads';

    /**
     * Before Validation Callback
     * @param array $options
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        // ignore empty file - causes issues with form validation when file is empty and optional
        if (!empty($this->data[$this->alias]['avatar']['error']) && $this->data[$this->alias]['avatar']['error'] == 4 && $this->data[$this->alias]['avatar']['size'] == 0) {
            unset($this->data[$this->alias]['avatar']);
        }

        return parent::beforeValidate($options);
    }

    /**
     * Before Save Callback
     * @param array $options
     * @return boolean
     */
    public function beforeSave($options = array()) {
        // a file has been uploaded so grab the filepath
        if (!empty($this->data[$this->alias]['filepath'])) {
            $this->data[$this->alias]['avatar'] = $this->data[$this->alias]['filepath'];
        }

        return parent::beforeSave($options);
    }

    /**
     * Process the Upload
     * @param array $check
     * @return boolean
     */
    public function processUpload($check = array()) {
        // deal with uploaded file
        if (!empty($check['avatar']['tmp_name'])) {

            // check file is uploaded
            if (!is_uploaded_file($check['avatar']['tmp_name'])) {
                return FALSE;
            }

            // build full img
            $img = WWW_ROOT . $this->uploadDir . DS . Inflector::slug(pathinfo($check['avatar']['name'], PATHINFO_FILENAME)) . '.' . pathinfo($check['avatar']['name'], PATHINFO_EXTENSION);

            // @todo check for duplicate img
            // try moving file
            if (!move_uploaded_file($check['avatar']['tmp_name'], $img)) {
                return FALSE;

                // file successfully uploaded
            } else {
                // save the file path relative from WWW_ROOT e.g. uploads/example_img.jpg
                $this->data[$this->alias]['filepath'] = str_replace(DS, "/", str_replace(WWW_ROOT, "", $img));
            }
        }

        return TRUE;
    }

}
