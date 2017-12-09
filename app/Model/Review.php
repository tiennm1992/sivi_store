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
            'fields' => array('SUM(Review.star) AS total_star'),
            'conditions' => $cond,
        ));
        if (!empty($total_star[0][0]['total_star'])) {
            $total_star = $total_star[0][0]['total_star'];
        } else {
            $total_star = 0;
        }
        return round($total_star / $count, 1);
    }

    public function get_comment($product_id, $last_id = 0, $limit = 10) {
        $cond = array(
            'Review.product_id' => $product_id,
        );
        if (!empty($last_id)) {
            $cond['Review.id <'] = $last_id;
        }
        $rep_data = $this->find('all', array(
            'conditions' => $cond,
            'limit' => $limit,
            'order'=>array("Review.id DESC")
        ));
        if (!empty($rep_data)) {
            return Set::extract('/Review/.', $rep_data);
        }
        return $rep_data;
    }

    public function get_all_star($product_id) {

        $one_star = $this->find('count', array(
            'conditions' => array(
                'Review.product_id' => $product_id,
                'Review.star' => 1,
            )
        ));
        $two_star = $this->find('count', array(
            'conditions' => array(
                'Review.product_id' => $product_id,
                'Review.star' => 2,
            )
        ));
        $three_star = $this->find('count', array(
            'conditions' => array(
                'Review.product_id' => $product_id,
                'Review.star' => 3,
            )
        ));
        $four_star = $this->find('count', array(
            'conditions' => array(
                'Review.product_id' => $product_id,
                'Review.star' => 4,
            )
        ));
        $five_star = $this->find('count', array(
            'conditions' => array(
                'Review.product_id' => $product_id,
                'Review.star' => 5,
            )
        ));
        $rep = array(
            'one_star' => $one_star,
            'two_star' => $two_star,
            'three_star' => $three_star,
            'four_star' => $four_star,
            'five_star' => $five_star,
        );
        return $rep;
    }

}
