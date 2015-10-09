<?php
/* 样例
    $data['full'] = array(
        'id' => 'full',
        'name' => '用户数据',
        'field_names' => array(  // 显示图形列表
            'u_id' => '用户id',
            'u_name' => '用户昵称',
            'u_register_time' => '创建时间',
        ),
        'charts' => array(      // 展示图形的信息
            'u_id' =>
            array(
                'id' => 'u_id',
                'name' => '用户id图表显示',
                'y' => 'total',
                'x' => 'date'
            )
        ),
        'links' => array('user_id' => '/dashboard?id=persion&user_id=%s'),
        'sql' => 'SELECT COUNT(DISTINCT `lottery`.`u_id`) AS `total`, DATE_FORMAT(`lottery`.`created_on`, '%Y-%m-%d') AS `date` FROM `jindan-a_dev`.`lottery` GROUP BY DATE_FORMAT(`lottery`.`created_on`, '%Y-%m-%d')',
        'database_service_name' => 'jindan'
    );

    $data['persion'] = array(
        'id' => 'persion',
        'name' => '个人用户',
        'query_string' => array('user_id'),
        'field_names' => array(
            'user_id' => '用户id',
            'nickname' => '用户昵称',
            'created_on' => '创建时间',
        ),
        'sql' => 'SELECT user_id, nickname, created_on, deleted_on FROM `jd-user`.users WHERE user_id = :user_id'
    );
*/

$data = array();
$data['add_lottery_total'] = array(
    'id' => 'user_count',
    'name' => '每日用户数',
    'field_names' => array(
        'dd' => '时间',
        'cc' => '用户数',
    ),
    'charts' => array(
        'user_add_lottery_total' => array(
            'id' => 'user_count',
            'name' => '每日用户总数',
            'y' => 'cc',
            'x' => 'dd'
        )
    ),
    'sql' => "SELECT COUNT(*) cc, u_date dd FROM users GROUP BY u_date LIMIT 100",
    'database_service_name' => 'qutouying',
);

