<?php

App::uses('AppModel', 'Model');

class Review extends AppModel {

    public $name = "Review";
    public $useTable = 'reviews';

    public function check_review($user_id, $product_id) {
        $cond = array(
            'Review.user_id' => $user_id,
            'Review.product_id' => $product_id,
        );
        $check = $this->find('first', array('conditions' => $cond));
        if ($check) {
            return $check['Review'];
        }
        return 0;
    }

    public function review_summary($product_id) {
        $cond = array(
            'Review.product_id' => $product_id,
        );
        $count = $this->find('count', array('conditions' => $cond));
        $total_star = $this->find('all', array(
            'fields' => array('SUM(Review.start) AS total_star'),
            'conditions' => $cond,
        ));
        if (!empty($total_star[0][0]['total_star'])) {
            $total_star = $total_star[0][0]['total_star'];
        } else {
            $total_star = 0;
        }
        return round($total_star/$count, 1);
    }

}
