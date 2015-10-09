<?php

class Controller {

    /**
     * __construct
     * 构架方法
     *
     * @var string
     */
    public function __construct() {
    }

    /**
     * login
     * 登录
     *
     * @var string
     */
    public static function login() {

        // 获取参数
        $body = Flight::request()->getBody();
        $error = '';

        // 判断登录态是否过期了
        $user = Util::get_user_config();
        $current_time = time();
        if ($current_time < $user['expir_time']) {
            Flight::redirect('/dashboard');
        }

        if ($body) {
            $username = Flight::request()->data->username;
            $password = Flight::request()->data->password;

            $path = Util::$user_config_path;

            // 判断用户是否存在
            if ($user['username'] != $username) {
                $error = '用户不存在';
            }

            // 判断密码是否正确
            if (!$error && $user['password'] != $password) {
                $user['password_error_num'] += 1;
                // 有错误次数就更新到这个里面
                file_put_contents($path, json_encode($user));
                $error = '密码错误';
            }

            if ($user['password_error_num'] > 2) {
                $error = '密码输入错误超过3次请联系池骋解决';
            }

            if (!$error) {
                // 增加过期时间
                $user['expir_time'] = time() + 86400;

                // 没有问题的话就将错误次数更新
                $user['password_error_num'] = 0;
                file_put_contents($path, json_encode($user));

                Flight::redirect('/dashboard');
            }
        }

        // 准备渲染的数据
        $result = array(
            'error' => $error,
            'username' => $username,
            'password' => $password
        );

        // 渲染页面
        Flight::render('login', $result);
    }

    /**
     * index
     * 统计方法
     *
     * @var string
     */
    public static function index() {
        // 验证登录态
        self::__verify_user_auth();

        // 获取参数
        $id = Flight::request()->query->id;
        $orderby = Flight::request()->query->orderby;
        $order = Flight::request()->query->order;

        // 获取配置
        $conf = Util::get_conf_data();

        // 没有要展示的统计默认展示第一个
        if (!$id && is_array($conf)) {
            $tmp_conf = $conf;
            $first_conf = array_shift($tmp_conf);
            $id = $first_conf['id'];
            Flight::view()->set('stats_id', $id);
        }

        // 根据语句处理拼接要是统计的数据
        $render_data = array();
        foreach ($conf as $k => $data) {

            if ($data['id'] == $id) {

                // 根据参数整理sql语句
                $orderby_sql = '';
                if ($orderby && $data['field_names'][$orderby]) {
                    if (!in_array($order, array('DESC', 'ASC'))) {
                        $order = 'DESC';
                    }
                    $orderby_sql = ' ORDER BY ' . $orderby . ' ' . $order;
                }

                $links = $data['links'];

                // 根据参数拼接limit
                $limit_sql = '';
                $date = array();
                if ($data['time_range']) {
                    switch ($data['time_range']) {
                        case 'last_month_date':
                            $limit_sql = ' LIMIT 0,31';
                            $date = self::__last_day($data['time_range']);
                            break;

                        default:
                            break;
                    }
                }

                // 拼接sql并且进行查询
                $sqls = $data['sqls'];
                $sql = $data['sql'];
                $params = array();
                $db = Flight::$data['database_service_name']();

                // 拼接sql获取数据
                $rebuilt_rows = array();
                if ($sqls) {
                    foreach ($sqls as $sql_value) {
                        $rows_data[] = self::__get_rows($db, $sql_value['sql'], $sql_value['query_string'], $limit_sql, $orderby_sql);
                    }
                } else {
                    $rebuilt_rows = self::__get_rows($db, $sql, $data['query_string'], $limit_sql, $orderby_sql);
                }

                // 获取要显示的字段
                $field_names = $data['field_names'];

                // 处理数据集合
                if ($rows_data) {
                    $same_field = $data['same_field'];

                    // 将统一的字段是按照最近30天还是最近7天附上值
                    foreach ($date as $date_value) {
                        $rebuilt_rows[$date_value] = array();
                    }

                    // 整理rows进行合并
                    foreach ($rows_data as $row) {
                        // 判断是否有row
                        if ($row) {
                            foreach ($row as $value) {
                                // 判断时间内是否有我最近30天的数据
                                if (in_array($value[$same_field], $date)) {

                                    $rebuilt_rows[$value[$same_field]] = array_merge($value, $rebuilt_rows[$value[$same_field]]);
                                }
                            }
                        }
                    }

                    // 再整理一下rows_data数据
                    foreach ($rebuilt_rows as $key => $rebuilt_row) {
                        foreach ($field_names as $field_name_key => $field_name) {
                            if ($field_name_key == $same_field) {
                                $rebuilt_rows[$key][$same_field] = $key;
                            } else {
                                if (!$rebuilt_row[$field_name_key]) {
                                    $rebuilt_rows[$key][$field_name_key] = 0;
                                }
                            }
                        }
                    }
                }

                // 拼接统计数据
                if ($data['charts'] && $rebuilt_rows) {
                    $chart_data = array();
                    foreach ($data['charts'] as $k => $chart_conf) {
                        $chart_data[$k] = $chart_conf;
                        if ($chart_conf['x'] && $chart_conf['y']) {
                            // 判断是否是要显示多条线
                            if ($rows_data) {
                                $chart_data[$k]['data']['x'] = $date;

                                // 整理y轴数据
                                foreach ($chart_conf['y'] as $y) {
                                    $rebuilt_y = array();
                                    foreach($rebuilt_rows as $rebuilt_row) {
                                        if (array_key_exists($y, $rebuilt_row)) {
                                            $rebuilt_y[] = $rebuilt_row[$y];
                                        } else {
                                            $rebuilt_y[] = 0;
                                        }
                                    }
                                    $rebuilt_y = "'" . join("','", $rebuilt_y) . "'";
                                    $chart_data[$k]['data']['y'][] = $rebuilt_y;
                                }

                                $chart_data[$k]['data']['x'] = "'" . join("','", $chart_data[$k]['data']['x']) . "'";
                            } else {
                                $rebuilt_y = array();
                                foreach($rebuilt_rows as $row) {
                                    $chart_data[$k]['data']['x'][] = $row[$chart_conf['x']];
                                    $rebuilt_y[] = $row[$chart_conf['y']];
                                }

                                $chart_data[$k]['data']['x'] = "'" . join("','", $chart_data[$k]['data']['x']) . "'";
                                $chart_data[$k]['data']['y'][] = "'" . join("','", $rebuilt_y) . "'";
                            }
                        }
                    }

                    $render_data['chart_data'] = $chart_data;
                }

                // 整理要渲染的数据
                $render_data['field_names'] = $field_names;
                $render_data['sql'] = $sql;
                $render_data['links'] = $links;
                $render_data['rows'] = $rebuilt_rows;
                $render_data['conf'] = $data;

                break;
            }
        }

        // 渲染模板
        Util::render_frame();
        Flight::render('index', $render_data, 'body_content');
        Flight::render('layout');
    }

    /**
     * setting_config
     * 设置config
     *
     * @var string
     */
    public static function setting_config() {
        self::__verify_user_auth();

        // 获取统计系统配置
        $config = Util::get_conf_data();

        // $result = array(
        //     'config' => json_encode($config),
        // );

        // 整理列表
        $result = array();
        foreach ($config as $key => $value) {
            $result[] = array(
                'config_id' => $value['id'],
                'config_name' => $value['name'],
            );
        }

        // 设置config信息
        Util::render_frame();
        // Flight::render('setting_config', $result);
        Flight::render('setting_config_list', array('config_list' => $result));
    }

    /**
     * show_update_setting_config
     * 删除要修改的配置
     *
     * @var string
     */
    public static function show_update_setting_config() {
        self::__verify_user_auth();

        $id = Flight::request()->query->id;

        // 获取统计系统配置
        $config = Util::get_conf_data();

        $result = array();

        // 判断配置是否存在
        if ($id && $config[$id]) {
            $result['config'] = json_encode($config[$id]);
        } else {
            $result['error'] = '配置不存在';
        }

        // 设置config信息
        Util::render_frame();
        Flight::render('show_update_setting_config', $result);
    }

    /**
     * update_setting_config
     * 修改设置config
     *
     * @var string
     */
    public static function update_setting_config() {
        // 检查用户权限
        self::__verify_user_auth();

        // 配置
        $result = self::__setting_config('update');

        echo json_encode($result);
    }

    /**
     * add_setting_config
     * 添加设置config
     *
     * @var string
     */
    public static function add_setting_config() {
        // 检查用户权限
        self::__verify_user_auth();

        // 配置
        $result = self::__setting_config('add');

        echo json_encode($result);
    }

    /**
     * show_add_setting_config
     * 显示添加设置config
     *
     * @var string
     */
    public static function show_add_setting_config() {
        // 检查用户权限
        self::__verify_user_auth();

        $result = array();
        $config = array(
            'id' => '配置的唯一id',
            'name' => '配置的名字',
            'field_names' => array(
                "字段1" => "字段的中文名字"
            ),
            "charts" => array(
                "统计表的id" => array(
                    "id" => "统计表的id",
                    "name" => "统计表的名字",
                    "y" => "对应y轴的字段",
                    "x" => "对应x轴的字段"
                )
            ),
            "sql" => "sql的值",
            "database_service_name" => "要连接的数据库",
            "time_range" => "数据的时间范围,现在只有last_month_date",
            "same_field" => "配置多字段折线的时候以什么字段为基准，例如都是以一个时间为基准",
            "is_show" => "是否展示该统计"
        );
        $result['config'] = json_encode($config);

        // 设置config信息
        Util::render_frame();
        Flight::render('show_add_setting_config', $result);
    }

    /**
     * delete_setting_config
     * 删除设置config
     *
     * @var string
     */
    public static function delete_setting_config() {
        self::__verify_user_auth();

        // 获取提交数据
        $id = Flight::request()->data->id;

        // 获取配置看是否存在
        $result = array();
        $config = Util::get_conf_data();
        if ($config[$id]) {
            // 删除配置
            unset($config[$id]);

            // 保存配置
            $is_success = true;
            // $is_success = self::__save_config($config);
            if ($is_success) {
                $result['success'] = '删除成功';
            } else {
                $result['error'] = '删除失败';
            }
        } else {
            $result['error'] = '配置不存在';
        }

        echo json_encode($result);
    }

    /**
     * __verify_user_auth
     * 验证用户授权
     *
     * @var string
     */
     private static function __verify_user_auth() {
        // 暂时就一个用户
        $user = Util::get_user_config();

        // 判断是否过期
        $current_time = time();
        if ((!$user['expir_time'] || $current_time > $user['expir_time']) || $user['password_error_num'] > 0) {
            Flight::redirect('/login');
        }
    }

    /**
     * __last_day
     * 最近天数
     *
     * @var string
     */
     private static function __last_day($time_range) {
        $day = 0;
        switch ($time_range) {
            case 'last_month_date':
                $day = 30;
                break;

            default:
                break;
        }

        // 获取最近天数
        $date = array();
        for ($i = 0; $i <= $day; $i ++) {
            $date[] = date("Y-m-d", strtotime('-' . $i . 'day'));
        }

        return $date;
     }

    /**
     * __get_rows
     * 根据sql获取数据
     *
     * @var string
     */
     private static function __get_rows($db, $sql, $query_string, $limit_sql, $orderby_sql) {
        $params = array();
        $rows = array();

        // 拼接sql
        $sql = $sql . $orderby_sql . $limit_sql;

       if ($query_string) {
            foreach ($query_string as $field) {
                $params[':' . $field] = Flight::request()->query->$field;
            }
        }

        // 根据sql查询数据
        $sth = $db->prepare($sql);
        $sth->execute($params);
        $rows = $sth->fetchAll();

        return $rows;
     }

    /**
     * __save_config
     * 保存配置
     *
     * @var string
     */
     private static function __save_config($config) {
        // 转换配置
        if (is_array($config)) {
            $config = json_encode($config);
        } else {
            return false;
        }

        // 判断配置是否存在
        if (!$config) {
            return false;
        }

        // 保存配置
        $path = Util::$statis_config_path;
        file_put_contents($path, $config);

        return true;
     }

     private static function __setting_config($type) {
        // 获取提交数据
        $config = Flight::request()->data->config;

        // 判断是否有提交数据
        $error = '';
        $success = '';
        if (!trim($config)) {
            $error = 'config不能为空';
        }

        // 判断提交的数据是否正确
        $rebuilt_config = json_decode($config, true);
        if (!$rebuilt_config) {
            $error = '配置格式错误,请选择编辑器中的code模式进行检查';
        }

        // 判断必要参数是否存在
        $id = $rebuilt_config['id'];
        if (!$error && !$id) {
            $error = '配置中id不能为空';
        }

        // 判断是添加还是修改
        $old_config = Util::get_conf_data();
        if ($type == 'update') {
            // 判断id是否存在
            if (!$error && !$old_config[$id]) {
                $error = '配置id不存在请刷新当前页面';
            }
        } else {
            if (!$error && $old_config[$id]) {
                $error = '配置id已存在';
            }
        }

        // 获取配置文件的路径
        $path = Util::$statis_config_path;

        // 没有错误的话就进行更新
        if (!$error) {
            $old_config[$id] = $rebuilt_config;
            file_put_contents($path, json_encode($old_config));
            $success = '成功';
        }

        $result = array(
            'error' => $error,
            'success' => $success
        );

        return $result;
     }
}
