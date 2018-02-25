<?php

App::uses('AppModel', 'Model');

class ViewRecently extends AppModel {

    public $name = "ViewRecently";
    public $useTable = 'view_recently';

    public function update_view_recently($user_id, $product_id) {
        $data_check = $this->find('first', array(
            'conditions' => array(
                'product_id' => $product_id,
                'user_id' => $user_id,
            )
        ));
        $data = array(
            'product_id' => $product_id,
            'user_id' => $user_id,
            'created_datetime' => date("Y-m-d H:i:s"),
        );
        if (!empty($data_check['ViewRecently'])) {
            $data['id'] = $data_check['ViewRecently']['id'];
        }
        $this->save($data);
    }

}
