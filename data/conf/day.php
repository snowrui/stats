<?php
// 日新增用户数
$data['day_register_user_for_jindan'] = array(
    'id' => 'day_register_user_for_jindan',
    'name' => '日注册用户',
    'field_names' => array(
        'dd' => '日期',
        'cc' => '用户数',
    ),
    'charts' => array(
        'day_register_user_for_jindan' => array(
            'id' => 'day_register_user_for_jindan',
            'name' => '日注册用户',
            'y' => 'cc',
            'x' => 'dd'
        )
    ),
    'sql' => "SELECT DATE(FROM_UNIXTIME(u_register_time)) AS dd, COUNT(u_id) cc FROM `jd-user`.user WHERE DATE(FROM_UNIXTIME(u_register_time)) >= '" . $last_month_date . "' GROUP BY DATE(FROM_UNIXTIME(u_register_time)) ORDER BY DATE(FROM_UNIXTIME(u_register_time)) DESC",
    'database_service_name' => 'jindan'
);

// 新增绑卡用户数
$data['day_bind_card_user_for_jindan'] = array(
    'id' => 'day_bind_card_user_for_jindan',
    'name' => '日绑卡用户数',
    'field_names' => array(
        'dd' => '日期',
        'cc' => '用户数',
    ),
    'charts' => array(
        'day_bind_card_user_for_jindan' => array(
            'id' => 'day_bind_card_user_for_jindan',
            'name' => '日注绑卡用户数',
            'y' => 'cc',
            'x' => 'dd'
        )
    ),
    'sql' => "SELECT DATE(ua_create_time) dd, COUNT(distinct jua.u_id) cc FROM `jd-java`.user_account jua WHERE jua.ua_bank_card IS NOT NULL AND DATE(ua_create_time) > '" . $last_month_date . "' GROUP BY DATE(ua_create_time) ORDER BY DATE(ua_create_time) DESC",
    'database_service_name' => 'jindan_java'
);

// 日提现用户数
$data['day_rollout_user_for_jindan'] = array(
    'id' => 'day_rollout_user_for_jindan',
    'name' => '日提现金额',
    'field_names' => array(
        'dd' => '日期',
        'cc' => '用户数',
        'ss' => '提现金额(w)',
    ),
    'charts' => array(
        'day_rollout_user_for_jindan' => array(
            'id' => 'day_rollout_user_for_jindan',
            'name' => '日提现金额数',
            'y' => 'ss',
            'x' => 'dd'
        )
    ),
    'sql' => "SELECT DATE(ff_complete_time) dd, COUNT(distinct u_id) cc, CEIL(SUM(ff.ff_money/1000000)) ss FROM `jd-java`.funds_flow ff WHERE ff_status IN (0, 1, 2) AND ff_trans_type = 'ROLLOUT' AND ff_money>9999 AND date(ff_complete_time) > '" . $last_month_date . "' GROUP BY DATE(ff_complete_time) ORDER BY DATE(ff_complete_time) DESC;",
    'database_service_name' => 'jindan_java'
);


// 充值金额
$data['day_rechange_user_for_jindan'] = array(
    'id' => 'day_rechange_user_for_jindan',
    'name' => '日充值金额',
    'field_names' => array(
        'dd' => '日期',
        'cc' => '用户数',
        'ss' => '充值金额',
    ),
    'charts' => array(
        'day_rechange_user_for_jindan' => array(
            'id' => 'day_rechange_user_for_jindan',
            'name' => '日充值金额数',
            'y' => 'ss',
            'x' => 'dd'
        )
    ),
    'sql' => "SELECT DATE(ff_complete_time) dd, COUNT(DISTINCT u_id) cc, CEIL(SUM(ff.ff_money/1000000)) ss FROM `jd-java`.funds_flow ff WHERE ff_status IN (0, 1, 2) AND ff_trans_type = 'RECHARGES' AND ff_money>9999 AND DATE(ff_complete_time) > '" . $last_month_date . "' GROUP BY DATE(ff_complete_time) ORDER BY DATE(ff_complete_time) DESC;",
    'database_service_name' => 'jindan_java'
);

// 新增投资用户
$data['day_new_rechange_user_for_jindan'] = array(
    'id' => 'day_new_rechange_user_for_jindan',
    'name' => '日新增投资用户',
    'field_names' => array(
        'dd' => '日期',
        'cc' => '用户数',
    ),
    'charts' => array(
        'day_new_rechange_user_for_jindan' => array(
            'id' => 'day_new_rechange_user_for_jindan',
            'name' => '日新增投资用户',
            'y' => 'cc',
            'x' => 'dd'
        )
    ),

    'sql' => " SELECT date(ff_create_time) dd, COUNT(DISTINCT U_ID) cc FROM funds_flow FF WHERE ff_status  in (0,1, 2) AND ff_trans_type='RECHARGES' AND date(ff_create_time) > '" . $last_month_date . "' AND ff_type IS NULL AND NOT EXISTS(SELECT 1 FROM funds_flow FF2 where FF2.ff_trans_type='RECHARGES' AND FF2.ff_type IS NULL AND FF.ua_id = FF2.UA_ID  AND ff_status  IN (0,1,2)  AND FF2.FF_CREATE_TIME < FF.ff_create_time) GROUP BY DATE(ff_create_time) ORDER BY DATE(ff_create_time) DESC",
    'database_service_name' => 'jindan_java'
);


