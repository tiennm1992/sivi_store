<?php

App::uses('AppModel', 'Model');

/**
 * UserBuy Model
 *
 * @property Customer $Customer
 * @property Product $Product
 */
class UserPosition extends AppModel {

    public $useTable = "user_position";

    public function update_level($user_id) {
        $User_model = ClassRegistry::init('User');
        $user_data = $User_model->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            )
        ));
        $date = date("Y-m");
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];
        $position_data = $this->find('first', array(
            'conditions' => array(
                "code" => $user_data['User']['code'],
                'month' => $month,
                'year' => $year,
            )
        ));
        $cur_position = !empty($position_data['UserPosition']['sasi_position']) ? $position_data['UserPosition']['sasi_position'] : 0;
        $cur_sub_position = !empty($position_data['UserPosition']['sasi_sub_position']) ? $position_data['UserPosition']['sasi_sub_position'] : 0;
        $condition_position = array();
        $up_position = array(
            'sasi_position' => $cur_position,
            'sasi_sub_position' => $cur_sub_position
        );

        //get condition
        switch ($cur_position) {
            case 0:// up to sasim
                $condition_position['level_1']['position'] = 0;
                $condition_position['level_1']['sub_position'] = 0;
                $condition_position['level_1']['num_position'] = 1;
                $condition_position['num_product'] = 1;
                $condition_position['num_level'] = 1;
                $up_position['sasi_position'] = 1;
                $up_position['sasi_sub_position'] = 0;
                break;
            case 1: //up to sasima
                $condition_position['level_1']['position'] = 1;
                $condition_position['level_1']['sub_position'] = 0;
                $condition_position['level_1']['num_position'] = 1;
                $condition_position['num_product'] = 4;
                $condition_position['num_level'] = 1;
                $up_position['sasi_position'] = 2;
                $up_position['sasi_sub_position'] = 0;
                break;
            case 2: //up to sasime
                $condition_position['num_product'] = 7;
                $condition_position['level_1']['position'] = 1;
                $condition_position['level_1']['sub_position'] = 0;
                $condition_position['level_1']['num_position'] = 1;
                $condition_position['level_2']['position'] = 2;
                $condition_position['level_2']['sub_position'] = 0;
                $condition_position['num_level'] = 2;
                $up_position['sasi_position'] = 3;
                switch ($cur_sub_position) {
                    case 0:
                        $condition_position['level_2']['num_position'] = 1;
                        $up_position['sasi_sub_position'] = 0;

                        break;
                    case 1:
                        $condition_position['level_2']['num_position'] = 3;
                        $up_position['sasi_sub_position'] = 1;
                        break;
                    case 2:

                        $condition_position['level_2']['num_position'] = 5;
                        $up_position['sasi_sub_position'] = 2;
                        break;
                }
                break;
            case 3:
                $condition_position['num_product'] = 7;
                $condition_position['level_1']['position'] = 1;
                $condition_position['level_1']['sub_position'] = 0;
                $condition_position['level_1']['num_position'] = 1;
                $condition_position['level_2']['position'] = 2;
                $condition_position['level_2']['sub_position'] = 0;
                $condition_position['level_2']['num_position'] = 1;
                $condition_position['level_3']['position'] = 3;
                $condition_position['level_3']['sub_position'] = 2;
                $up_position['sasi_position'] = 4;
                $condition_position['num_level'] = 3;
                switch ($cur_sub_position) {
                    case 0:
                        $condition_position['level_3']['num_position'] = 1;
                        $up_position['sasi_sub_position'] = 0;
                        break;
                    case 1:
                        $condition_position['level_3']['num_position'] = 5;
                        $up_position['sasi_sub_position'] = 1;
                        break;
                    case 2:
                        $condition_position['level_3']['num_position'] = 10;
                        $up_position['sasi_sub_position'] = 2;
                        break;
                    case 3:
                        $condition_position['level_3']['num_position'] = 20;
                        $up_position['sasi_sub_position'] = 3;
                        break;
                }
                break;
        }
        $check_num = $this->check_number_product($user_data['User']['code'], $condition_position);
        $check_low_employee = $this->check_low_employee($user_data['User']['code'], $condition_position);
        if ($check_low_employee && $check_nums) {
            //dc thang cap
            $save_data = array();
            if (!empty($position_data['UserPosition'])) {
                $save_data = $position_data['UserPosition'];
            } else {
                $save_data['code'] = $user_data['User']['code'];
                $save_data['month'] = $month;
                $save_data['user_id'] = $user_id;
                $save_data['year'] = $year;
            }
            $save_data['sasi_position'] = $up_position['sasi_position'];
            $save_data['sasi_sub_position'] = $up_position['sasi_sub_position'];
            if ($this->save($save_data)) {
                $this->update_level();
            }
        }

        //update level for manage
    }

    public function check_number_product($code, $condition_position) {
        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $num_buy_data = $User_buy->find('count', array(
            'conditions' => array(
                'UserBuy.code' => $code,
                'UserBuy.status' => 2,
                "UserBuy.date <= '{$end_date}'",
                "UserBuy.date > '{$start_date}'",
            )
        ));
        if ($num_buy_data >= $condition_position['num_product']) {
            return 1;
        }
        return 0;
    }

    public function check_low_employee($code, $condition_position) {
        $UserModel = ClassRegistry::init('User');
        $level_data = $UserModel->find('all', array(
            'conditions' => array(
                'User.sale_id_protected' => $code
            ),
            'joins' => array(
                array(
                    'table' => 'user_position',
                    'alias' => "User_pos",
                    'type' => 'inner',
                    'conditions' => array(
                        'User_pos.code = User.code'
                    )
                )
            )
        ));
        if ($condition_position['num_level'] == 1) {
            $num_position = 0;
            foreach ($level_data as $key => $value) {
                if ($value['User_pos']['sasi_position'] == $condition_position['level_1']['position'] && $value['User_pos']['sasi_sub_position'] == $condition_position['level_1']['sub_position']) {
                    $num_position += 1;
                }
            }
            if ($num_position >= $condition_position['level_1']['num_position']) {
                return 1;
            }
            return 0;
        }
        if ($condition_position['num_level'] == 2) {
            $num_level_1 = 0;
            $num_level_2 = 0;
            foreach ($level_data as $key => $value) {
                if ($value['User_pos']['sasi_position'] == $condition_position['level_1']['position'] && $value['User_pos']['sasi_sub_position'] == $condition_position['level_1']['sub_position']) {
                    $num_level_1 += 1;
                }
                if ($value['User_pos']['sasi_position'] == $condition_position['level_2']['position'] && $value['User_pos']['sasi_sub_position'] == $condition_position['level_2']['sub_position']) {
                    $num_level_2 += 1;
                }
            }
            if ($num_level_1 >= $condition_position['level_1']['num_position'] && $num_level_2 >= $condition_position['level_2']['num_position']) {
                return 1;
            }
            return 0;
        }
        if ($condition_position['num_level'] == 3) {
            $num_level_1 = 0;
            $num_level_2 = 0;
            $num_level_3 = 0;
            foreach ($level_data as $key => $value) {
                if ($value['User_pos']['sasi_position'] == $condition_position['level_1']['position'] && $value['User_pos']['sasi_sub_position'] == $condition_position['level_1']['sub_position']) {
                    $num_level_1 += 1;
                }
                if ($value['User_pos']['sasi_position'] == $condition_position['level_2']['position'] && $value['User_pos']['sasi_sub_position'] == $condition_position['level_2']['sub_position']) {
                    $num_level_2 += 1;
                }
                if ($value['User_pos']['sasi_position'] == $condition_position['level_3']['position'] && $value['User_pos']['sasi_sub_position'] == $condition_position['level_3']['sub_position']) {
                    $num_level_3 += 1;
                }
            }
            if ($num_level_1 >= $condition_position['level_1']['num_position'] && $num_level_2 >= $condition_position['level_2']['num_position'] && $num_level_3 >= $condition_position['level_3']['num_position']) {
                return 1;
            }
            return 0;
        }
    }

    //lay điểm
    public function get_point() {
        
    }

    //doanh thu
    public function get_revenue($user_code, $type_price = "c0") {

        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $conditions = array();
        $conditions['UserBuy.code'] = $user_code;
        $conditions['UserBuy.status'] = 2;
        $conditions['UserBuy.date <='] = $end_date;
        $conditions['UserBuy.date >'] = $start_date;

        if ($type_price == 'c0') {
            $fields = array(" SUM(UserBuy.c0)  as sum ");
        } elseif ($type_price == 'c1') {
            $fields = array(" SUM(UserBuy.partner_price)  as sum ");
        } elseif ($type_price == 'c2') {
            $fields = array(" SUM(UserBuy.employee_price)  as sum ");
        }
        $user_profit = $User_buy->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions
        ));
        if (!empty($user_profit[0][0]['sum'])) {
            return $user_profit[0][0]['sum'];
        }
        return 0;
    }

    //loi nhuan
    public function get_profit($user_code) {
        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $conditions = array();
        $conditions['UserBuy.code'] = $user_code;
        $conditions['UserBuy.status'] = 2;
        $conditions['UserBuy.date <='] = $end_date;
        $conditions['UserBuy.date >'] = $start_date;
        $user_profit = $User_buy->find('all', array(
            'fields' => array(" SUM(UserBuy.revenue)  as sum "),
            'conditions' => $conditions
        ));
        if (!empty($user_profit[0][0]['sum'])) {
            return $user_profit[0][0]['sum'];
        }
        return 0;
    }

}
