<?php
/**
 * 用户授权配置
 *
*/
$users = array(
    'root' => array(
        'username' => 'root',
        'password' => 'root12',
        'expir_time' => time() + 86400,
    )
);

return $users;
