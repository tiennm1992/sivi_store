<?php

App::uses('AppModel', 'Model');

class ViewRecently extends AppModel {

    public $name = "ViewRecently";

    public function update_view_recently($user_id, $product_id) {
        $data = array(
            'product_id' => $product_id,
            'user_id' => $user_id,
            'created_datetime' => date("Y-m-d H:i:s"),
        );
        $this->save($data);
    }

}
