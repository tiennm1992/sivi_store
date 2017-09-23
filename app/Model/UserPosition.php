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

    public function update_level($user_id = 0) {
        $User_model = ClassRegistry::init('User');
        $user_data = $User_model->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            )
        ));
        if (empty($user_data)) {
            return 1;
        }
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
                $condition_position['num_level'] = 0;
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
        pr($check_num);
        pr($check_low_employee);die;
        if ($check_low_employee && $check_num) {
            //dc thang cap
            $save_data = array();
            if (!empty($position_data['UserPosition'])) {
                $save_data = $position_data['UserPosition'];
            } else {
                $save_data['code'] = $user_data['User']['code'];
                $save_data['month'] = $month;
                $save_data['user_id'] = $user_id;
                $save_data['year'] = $year;
                $save_data['sale_id_protected'] = $user_data['User']['sale_id_protected'];
            }
            $save_data['sasi_position'] = $up_position['sasi_position'];
            $save_data['sasi_sub_position'] = $up_position['sasi_sub_position'];
            if ($this->save($save_data)) {
                $this->update_level($user_data['User']['sale_id_protected']);
            }
        } else {
            if (empty($position_data)) {
                $position_tmp = array(
                    "user_id" => $user_data['User']['id'],
                    "code" => $user_data['User']['code'],
                    'month' => $month,
                    'year' => $year,
                    'sale_id_protected' => $user_data['User']['sale_id_protected'],
                );
                $this->save($position_tmp);
                $this->clear();
            }
        }


        //update level for manage
    }

    public function update_revenue($user_id) {
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
        if (!empty($position_data)) {
            $position_data = $position_data['UserPosition'];
            //get revenue
            $position_data['revenue'] = $this->get_revenue($user_data['User']['code']);
            $profit_type = 'c0';
            switch ($position_data['sasi_position']) {
                case 0:// up to sasim
                    $profit_type = 'c0';
                    break;
                case 1: //up to sasima
                    $profit_type = 'c0';
                    break;
                case 2: //up to sasime
                    $profit_type = 'c0';
                case 3:
                    $profit_type = 'c0';
            }
            //get profit
            $position_data['profit'] = $this->get_profit($user_data['User']['code'], $profit_type);
            //update point
            $position_data['point_dr'] = round($position_data['revenue'] / 1000);
            $this->save($position_data);
        }
    }

    public function check_number_product($code, $condition_position) {
        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m") . "-31 00:00:00";
        ;
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
            'fields' => array("User_pos.*", "User.*"),
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
        if (!empty($level_data)) {
            if ($condition_position['num_level'] == 0) {
                $check = $UserModel->find('all', array(
                    'conditions' => array(
                        'User.sale_id_protected' => $code
                    ),
                ));
                if (count($check) > 1) {
                    return 1;
                }
                return 0;
            }
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
        return 0;
    }

    //lay điểm
    public function get_point() {
        
    }

    //doanh thu
    public function get_profit($user_code, $type_price = "c0") {
        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m") . "-31 00:00:00";
        $start_date = date("Y-m") . "-1 00:00:00";
        $conditions = array();
        $conditions['UserBuy.code'] = $user_code;
        $conditions['UserBuy.status'] = 2;
        $conditions['UserBuy.date <='] = $end_date;
        $conditions['UserBuy.date >'] = $start_date;

        $user_profit = $User_buy->find('all', array(
            'conditions' => $conditions
        ));
        $profit = 0;
        foreach ($user_profit as $key => $value) {
            if ($type_price == 'c0') {
                $profit += $value['UserBuy']['price_sale'] - $value['UserBuy']['c0'];
            } elseif ($type_price == 'c1') {
                $profit += $value['UserBuy']['price_sale'] - $value['UserBuy']['partner_price'];
            } elseif ($type_price == 'c2') {
                $profit += $value['UserBuy']['price_sale'] - $value['UserBuy']['employee_price'];
            }
        }

        return $profit;
    }

    //loi nhuan
    public function get_revenue($user_code) {
        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m") . "-31 00:00:00";
        $start_date = date("Y-m") . "-1 00:00:00";
        $conditions = array();
        $conditions['UserBuy.code'] = $user_code;
        $conditions['UserBuy.status'] = 2;
        $conditions['UserBuy.date <='] = $end_date;
        $conditions['UserBuy.date >'] = $start_date;
        $user_profit = $User_buy->find('all', array(
            'fields' => array(" SUM(UserBuy.price_sale * UserBuy.number_product )  as sum "),
            'conditions' => $conditions
        ));
        if (!empty($user_profit[0][0]['sum'])) {
            return $user_profit[0][0]['sum'];
        }
        return 0;
    }

    public function get_sub_position_list($user_code) {
        $date = date("Y-m");
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];
        $position_data = $this->find('all', array(
            'conditions' => array(
                "sale_id_protected" => $user_code,
                'month' => $month,
                'year' => $year,
            )
        ));
        $rep_data = array(
            'count' => 0,
            'newbie' => 0,
            'sasi' => 0,
            'sasim' => 0,
            'sasima' => 0,
            'sasime' => 0,
            'sasimi' => 0,
            'sasimo' => 0,
            'sasimu' => 0,
        );
        if (!empty($position_data)) {
            foreach ($position_data as $key => $value) {
                switch ($value['UserPosition']['sasi_position']) {
                    case 0:// up to sasim
                        $rep_data['sasi'] += 1;
                        break;
                    case 1:// up to sasim
                        $rep_data['sasim'] += 1;
                        break;
                    case 2: //up to sasima
                        $rep_data['sasima'] += 1;
                        break;
                    case 3: //up to sasime
                        $rep_data['sasime'] += 1;
                        break;
                    case 4:
                        $rep_data['sasimi'] += 1;
                        break;
                    case 5:
                        $rep_data['sasimo'] += 1;
                        break;
                    case 6:
                        $rep_data['sasimu'] += 1;
                        break;
                }
            }
        }
        $end_date = date("Y-m-d H:s:i");
        $start_date = date("Y-m") . "-1 00:00:00";
        $User_model = ClassRegistry::init('User');
        $rep_data['newbie'] = $User_model->find('count', array(
            'conditions' => array(
                'User.sale_id_protected' => $user_code,
                "User.created_datetime <= '{$end_date}'",
                "User.created_datetime > '{$start_date}'",
            )
        ));
        $rep_data['count'] = $User_model->find('count', array(
            'conditions' => array(
                'User.sale_id_protected' => $user_code,
            )
        ));
        return $rep_data;
    }

    public function update_cc_for_boss($buy_data, $user_code) {
        $date = date("Y-m");
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];
        $position_data = $this->find('first', array(
            'conditions' => array(
                "code" => $user_code,
                'month' => $month,
                'year' => $year,
            )
        ));
        $user_data = $this->find('first', array(
            'conditions' => array(
                "code" => $buy_data['code'],
                'month' => $month,
                'year' => $year,
            )
        ));
        if (!empty($position_data['UserPosition']) && $position_data['UserPosition']['sasi_position'] > 1 && !empty($user_data['UserPosition'])) {
            $position = $user_data['UserPosition']['sasi_position'];
            $profit_add = 0;
            if (($position == 1 || $position == 0) && $position_data['UserPosition']['sasi_position'] > 1) {
                $profit_add = $buy_data['c0'] - $buy_data['partner_price'];
            } elseif ($position == 2 && $position_data['UserPosition']['sasi_position'] > 2) {
                $profit_add = $buy_data['c0'] - $buy_data['employee_price'];
            }
            $position_data['UserPosition']['profit_cc'] += $profit_add;
        }
        $this->save($position_data);
    }

    public function convert_position($position, $sub_positision) {
        $current_position = 'sasi';
        switch ($position) {
            case 0:// up to sasim
                $current_position = 'sasi';
                break;
            case 1:// up to sasim
                $current_position = 'sasim';
                break;
            case 2: //up to sasima
                $current_position = 'sasima';
                break;
            case 3: //up to sasime
                $current_position = 'sasime';
                switch ($sub_positision) {
                    case 0:
                        break;
                    case 1:
                        break;
                    case 2:
                        break;
                }
                break;
            case 4:
                $current_position = 'sasimi';
                switch ($sub_positision) {
                    case 0:
                        break;
                    case 1:
                        break;
                    case 2:
                        break;
                    case 3:
                        break;
                }
                break;
        }
        return $current_position;
    }

}
