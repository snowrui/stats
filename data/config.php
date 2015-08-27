<?php
$data = array();
$data['full'] = array(
    'id' => 'full',
    'name' => '全表数据',
    'field_names' => array(
        'user_id' => '用户id',
        'nickname' => '用户昵称',
        'created_on' => '创建时间',
        'deleted_on' => '删除时间',
    ),
    'charts' => array(
        'user_id' =>
        array(
            'id' => 'user_id',
            'name' => '用户id图表显示',
            'y' => 'user_id',
            'x' => 'created_on'
        )
    ),
    'links' => array('user_id' => '/dashboard?id=persion&user_id=%s'),
    'sql' => 'SELECT user_id, nickname, created_on, deleted_on FROM users'
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
    'sql' => 'SELECT user_id, nickname, created_on, deleted_on FROM users WHERE user_id = :user_id'
);


return $data;
