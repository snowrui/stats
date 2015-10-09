<?php

class Util {
    /**
     * statis_config_path
     * 统计的配置
     *
     * @var string
     */
    static $statis_config_path = '/data/logs/boss/config.json';

    /**
     * user_config_path
     * 渲染
     *
     * @var string
     */
    static $user_config_path = '/data/logs/boss/auth.json';

    /**
     * render_frame
     * 渲染
     *
     * @var string
     */
    public static function render_frame($title = '') {

        $sidebar = self::get_conf_data();
        Flight::render('header', array('title' => $title), 'header_content');
        Flight::render('navbar', array(), 'navbar_content');
        Flight::render('sidebar', array('sidebar' => $sidebar), 'sidebar_content');
    }

    /**
     * get_conf_data
     * 获取配置
     *
     * @var string
     */
    public static function get_conf_data() {

        // 引导config配置文件
        $conf = json_decode(file_get_contents(self::$statis_config_path), true);
        return $conf;
    }

    /**
     * get_user_config
     * 获取用户配置
     *
     * @var string
     */
    public static function get_user_config() {

        // 引导config配置文件
        $user = json_decode(file_get_contents(self::$user_config_path), true);
        return $user;
    }
}
