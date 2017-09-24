<?php

App::uses('AppModel', 'Model');

/**
 * UserBuy Model
 *
 * @property Customer $Customer
 * @property Product $Product
 */
class UserLevel extends AppModel {

    public $useTable = "user_position";

    public function update_level($code) {
        $User_model = ClassRegistry::init('User');
        $User_buy = ClassRegistry::init('UserBuy');
        $user_data = $User_model->find('first', array(
            'conditions' => array(
                'User.code' => $code
            )
        ));
        if (empty($user_data)) {
            return 0;
        }
        $number_product = $User_buy->get_number_buy($code);
        $arr = $this->get_sub_level($code);
        $level = $this->conditions_level($arr, $number_product);
        $profit = 0;
        $profit_type = 'c0';
        if ($level >= 10) {
            switch ($level) {
                case 10:// up to sasim
                    $profit_type = 'c0';
                    break;
                case 20: //up to sasima
                    $profit_type = 'c0';
                    break;
                case 30: //up to sasime
                case 31:
                case 32:
                    $profit_type = 'c1';
                    break;
                case 40:
                case 41:
                case 42:
                case 43:
                    $profit_type = 'c2';
                    break;
            }
            //get profit
            $profit = $this->get_profit($user_data['User']['code'], $profit_type);
        }

        //update revenue
        $revenue = $this->get_revenue($user_data['User']['code']);
        //update level
        $point_dr = round($revenue / 1000);
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
            $save_data = $position_data['UserLevel'];
            $save_data['level'] = $level;
            $save_data['profit'] = $profit;
            $save_data['revenue'] = $revenue;
            $save_data['point_dr'] = $point_dr;
        } else {
            $save_data = array(
                "user_id" => $user_data['User']['id'],
                "code" => $user_data['User']['code'],
                'month' => $month,
                'year' => $year,
                'sale_id_protected' => $user_data['User']['sale_id_protected'],
                'level' => $level,
                'profit' => $profit,
                'revenue' => $revenue,
                'point_dr' => $point_dr,
            );
        }
        if ($this->save($save_data)) {
            $this->update_level($user_data['User']['sale_id_protected']);
        }
    }

    public function conditions_level($cond, $number_porduct) {
        $level = 0;
        if ($number_porduct >= 1 && ($cond['sasi'] + $cond['sasim'] + $cond['sasima'] + $cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 1) {
            $level = 10;
        }
        if ($number_porduct >= 4 && ($cond['sasim'] + $cond['sasima'] + $cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 1) {
            $level = 20;
        }
        if ($number_porduct >= 7) {

            if (($cond['sasima'] + $cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 1) {
                $level = 30;
            }
            if (($cond['sasima'] + $cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 3) {
                $level = 31;
            }
            if (($cond['sasima'] + $cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 5) {
                $level = 32;
            }
        }
        if ($number_porduct >= 10) {

            if (($cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 1) {
                $level = 40;
            }
            if (($cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 5) {
                $level = 41;
            }
            if (($cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 10) {
                $level = 42;
            }
            if (($cond['sasime'] + $cond['sasimi'] + $cond['sasimo'] + $cond['sasimu']) >= 20) {
                $level = 42;
            }
        }
        return $level;
    }

    public function get_number_product($code) {
        $User_buy = ClassRegistry::init('UserBuy');
        $end_date = date("Y-m") . "-31 00:00:00";
        $start_date = date("Y-m") . "-1 00:00:00";
        $num_buy_data = $User_buy->find('count', array(
            'conditions' => array(
                'UserBuy.code' => $code,
                'UserBuy.status' => 2,
                "UserBuy.date <= '{$end_date}'",
                "UserBuy.date > '{$start_date}'",
            )
        ));
        return $num_buy_data;
    }

    public function get_sub_level($code) {
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
                    'type' => 'left',
                    'conditions' => array(
                        'User_pos.code = User.code'
                    )
                )
            )
        ));
        $arr = array(
            'sasi' => 0,
            'sasim' => 0,
            'sasima' => 0,
            'sasime' => 0,
            'sasimi' => 0,
            'sasimo' => 0,
            'sasimu' => 0,
        );
        if (!empty($level_data)) {
            foreach ($level_data as $key => $value) {
                if (empty($value['User_pos']['level'])) {
                    $arr['sasi'] += 1;
                } elseif ($value['User_pos']['level'] == 10) {
                    $arr['sasim'] += 1;
                } elseif ($value['User_pos']['level'] == 20) {
                    $arr['sasima'] += 1;
                } elseif ($value['User_pos']['level'] == 30 || $value['User_pos']['level'] == 31 || $value['User_pos']['level'] == 32) {
                    $arr['sasime'] += 1;
                } elseif ($value['User_pos']['level'] == 40 || $value['User_pos']['level'] == 41 || $value['User_pos']['level'] == 42 || $value['User_pos']['level'] == 43) {
                    $arr['sasimi'] += 1;
                } elseif ($value['User_pos']['level'] == 50 || $value['User_pos']['level'] == 51 || $value['User_pos']['level'] == 52 || $value['User_pos']['level'] == 53) {
                    $arr['sasimo'] += 1;
                } elseif ($value['User_pos']['level'] == 60 || $value['User_pos']['level'] == 61 || $value['User_pos']['level'] == 62 || $value['User_pos']['level'] == 63) {
                    $arr['sasimu'] += 1;
                }
            }
        }
        return $arr;
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
                $profit += ( $value['UserBuy']['price_sale'] - $value['UserBuy']['c0']) * $value['UserBuy']['number_product'];
            } elseif ($type_price == 'c1') {
                $profit += ($value['UserBuy']['price_sale'] - $value['UserBuy']['partner_price']) * $value['UserBuy']['number_product'];
            } elseif ($type_price == 'c2') {
                $profit += ($value['UserBuy']['price_sale'] - $value['UserBuy']['employee_price']) * $value['UserBuy']['number_product'];
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
                switch ($value['UserLevel']['level']) {
                    case 0:// up to sasim
                        $rep_data['sasi'] += 1;
                        break;
                    case 10:// up to sasim
                        $rep_data['sasim'] += 1;
                        break;
                    case 20: //up to sasima
                        $rep_data['sasima'] += 1;
                        break;
                    case 30: //up to sasime
                    case 31: //up to sasime
                    case 32: //up to sasime
                        $rep_data['sasime'] += 1;
                        break;
                    case 40:
                    case 41:
                    case 42:
                    case 43:
                        $rep_data['sasimi'] += 1;
                        break;
                    case 50:
                    case 51:
                    case 52:
                    case 53:
                        $rep_data['sasimo'] += 1;
                        break;
                    case 60:
                    case 61:
                    case 62:
                    case 63:
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

    public function update_cc_for_boss($buy_data, $boss_code) {
        $date = date("Y-m");
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];
        $boss_data = $this->find('first', array(
            'conditions' => array(
                "code" => $boss_code,
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
        $profit_add = 0;
        if (!empty($boss_data)) {
            $boss_data = $boss_data['UserLevel'];
            switch ($boss_data['level']) {
                case 0:// up to sasim
                    break;
                case 10:// up to sasim
                    break;
                case 20: //up to sasima
                    break;
                case 30: //up to sasime
                case 31: //up to sasime
                case 32: //up to sasime
                    if ($user_data['UserLevel']['level'] >= 10) {
                        $profit_add = $buy_data['c0'] - $buy_data['partner_price'];
                    }
                    break;
                case 40:
                case 41:
                case 42:
                case 43:
                    if ($user_data['UserLevel']['level'] >= 30) {
                        $profit_add = $buy_data['c0'] - $buy_data['employee_price'];
                    } elseif ($user_data['UserLevel']['level'] >= 10) {
                        $profit_add = $buy_data['c0'] - $buy_data['partner_price'];
                    }
                    break;
            }
            $boss_data['profit_cc'] += $profit_add;
            $this->save($boss_data);
        }
    }

    public function convert_position($level) {
        $current_position = 'sasi';
        switch ($level) {
            case 0:// up to sasim
                $current_position = 'sasi';
                break;
            case 10:// up to sasim
                $current_position = 'sasim';
                break;
            case 20: //up to sasima
                $current_position = 'sasima';
                break;
            case 30: //up to sasime
            case 31: //up to sasime
            case 32: //up to sasime
                $current_position = 'sasime';
                break;
            case 40:
            case 41:
            case 42:
            case 43:
                $current_position = 'sasimi';
                break;
            case 50:
            case 51:
            case 52:
            case 53:
                $current_position = 'sasimo';
                break;
            case 60:
            case 61:
            case 62:
            case 63:
                $current_position = 'sasimu';
                break;
        }
        return $current_position;
    }

}
