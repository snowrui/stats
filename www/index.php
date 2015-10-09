<?php

// 导入文件
require '../vendor/autoload.php';
include 'Util.php';
include 'Controller.php';

// 判断开发环境 禁止报php错误
if ($_SERVER['RUN_MODE'] == 'production') {
    error_reporting(E_ALL & ~E_NOTICE  & ~E_STRICT & ~E_DEPRECATED);


    // 数据库连接 php数据库
    Flight::register('qutouying', 'PDO', array('mysql:host=192.168.1.1;','root','root12',
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')), function($db){
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    });

} else {
    Flight::register('qutouying', 'PDO', array('mysql:host=192.168.1.2;','root','root12',
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')), function($db){
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    });
}

// 模板路径
Flight::set('flight.views.path', '../views');

// 设置路由
Flight::route('/', array('Controller', 'index'));
Flight::route('/dashboard', array('Controller', 'index'));
Flight::route('/login', array('Controller', 'login'));
Flight::route('/settings', array('Controller', 'setting_config'));
Flight::route('/update_setting_config', array('Controller', 'update_setting_config'));
Flight::route('/delete_setting_config', array('Controller', 'delete_setting_config'));
Flight::route('/show_update_setting_config', array('Controller', 'show_update_setting_config'));
Flight::route('/show_add_setting_config', array('Controller', 'show_add_setting_config'));
Flight::route('/add_setting_config', array('Controller', 'add_setting_config'));

// 运行
Flight::start();

