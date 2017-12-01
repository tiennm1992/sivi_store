<?php

App::uses('AppModel', 'Model');

class SocialCount extends AppModel {

    public $name = "SocialCount";
    public $useTable = 'social_count';

    public function check_like($user_id, $product_id) {
        $cond = array(
            'UserLike.user_id' => $user_id,
            'UserLike.product_id' => $product_id,
            'UserLike.status' => 1,
        );
        $check = $this->find('first', array('conditions' => $cond));
        if ($check) {
            return 1;
        }
        return 0;
    }

    public function status($user_id, $product_id, $status) {
        try {
            $cond = array(
                'user_id' => $user_id,
                'product_id' => $product_id,
            );
            $data = $this->find('first', array('conditions' => $cond));
            if ($data) {
                $data['UserLike']['status'] = $status;
                $this->save($data['UserLike']);
                return 1;
            } else {
                $arr = array(
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'status' => $status
                );
                $this->save($arr);
                return 1;
            }
            return 0;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //
    public function social_action($user_id, $product_id, $type, $action) {
        try {
            $cond = array(
                'user_id' => $user_id,
                'product_id' => $product_id,
            );
            $data = $this->find('first', array('conditions' => $cond));
            if ($data) {
                if ($action == 'like') {
                    if ($type) {
                        $data['SocialCount']['like']+=1;
                    } else {
                        if (!empty($data['SocialCount']['like'])) {
                            $data['SocialCount']['like'] -=1;
                        }
                    }
                } else {
                    if ($type) {
                        $data['SocialCount']['favorite']+=1;
                    } else {
                        if (!empty($data['SocialCount']['favorite'])) {
                            $data['SocialCount']['favorite'] -=1;
                        }
                    }
                }
                $this->save($data['SocialCount']);
                return 1;
            } else {
                $arr = array(
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                );
                if ($action == 'like') {
                    if ($type) {
                        $arr['like']+=1;
                    }
                } else {
                    if ($type) {
                        $arr['favorite']+=1;
                    }
                }
                $this->save($arr);
                return 1;
            }
            return 0;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
