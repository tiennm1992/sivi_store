<?php

App::uses('AppModel', 'Model');
App::uses('UserLike', 'Model');

/**
 * Product Model
 *
 * @property Category $Category
 * @property Subcategory $Subcategory
 */
class Product extends AppModel {

    public $name = "Product";

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
        ),
        'Subcategory' => array(
            'className' => 'Subcategory',
            'foreignKey' => 'subcategory_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function getHome($page, $type) {
        switch ($type) {
            case 'new':
                break;
            case 'hot':
                break;
            case 'suggest':
                break;
            case 'detail':
                $data = $this->getDetail($page);
                return $data;
                break;
            default:
                break;
        }

//        $arr = array(
////            'conditions' => array('Model.field' => $thisValue), 
////            'recursive' => 1, 
////            'fields' => array('Model.field1', 'DISTINCT Model.field2'),
//            //string or array defining order
////            'order' => array('Model.created', 'Model.field3 DESC'),
////            'group' => array('Model.field'), //fields to GROUP BY
//            'limit' => 2, //int
//            'page' => 0, //int
////            'offset' => n, //int
////            'callbacks' => true //other possible values are false, 'before', 'after'
//        );
//        $data = $this->find('all', $arr);
//        return $data;
    }

    //lay trang chi tiet
    public function getDetail($page) {
        $arr = array(
            'id' => 'Product.id',
            'Product.name',
            'Product.description',
            'Product.avatar',
            'Product.price',
//            'Product.price',
            'Product.sale',
//            'Product.date_create',
//            'Product.img',
//            'Product.category_id',
//            'Product.subcategory_id',
//            'Product.status',
//            'Product.storage',
//            'Product.product_from',
//            'Product.sort',
//            'Product.user_view',
//            'Product.user_like',
//            'Product.user_share',
        );
//        $this->unbindModel(array('belongsTo' => array('Category', 'Subcategory')), true);
//        $this->Product->unbindModel(
//                array('hasMany' => array('Follower'))
//        );
        $arr = array(
            'fields' => $arr,
            'limit' => 10, //int
            'page' => $page, //int
            'recursive' => 1,
        );
        $data = $this->find('all', $arr);
        $data_api = array();
        foreach ($data as $key => $value) {
            $data_api[] = $value['Product'];
        }
        return $data_api;
    }

    public function getDetailProduct($id, $user_id = 0) {
        $arr = array(
            'conditions' => array('Product.id' => $id),
        );
        $data = $this->find('all', $arr);
        $data = $data[0]['Product'];
        $img = explode('|', $data['img']);
        $img = array_filter($img);
        $img_arr = array();
        foreach ($img as $key => $value) {
            $img_arr[] = 'http://sivistore.com/' . $value;
        }
        $data['img'] = $img_arr;
        $data['price'] = number_format($data['price'], 0, ',', '.');
        $model = new UserLike();
        $data['liked'] = ''.$model->check_like($user_id, $id);
        return $data;
    }

    public function getHome1($page) {
        $data = $this->find('all', array('fields' => array('Product.id')));
        return $data;
    }

}
