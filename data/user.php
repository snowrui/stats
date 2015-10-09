<?php
/**
 * 用户授权配置
 *
*/
$users = array(
    'jindan_user' => array(
        'username' => 'jindan_user',
        'password' => 'jindan123qaz',
        'expir_time' => time() + 86400,
    )
);

return $users;
