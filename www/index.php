<?php

require '../vendor/autoload.php';

Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=qutouying','root','root',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')), function($db){
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});
Flight::set('flight.views.path', '../views');

Flight::route('/', array('Controller', 'index'));
Flight::route('/dashboard', array('Controller', 'index'));
Flight::route('/dashboard/add', array('Controller', 'add_dashboard'));
Flight::route('/dashboard/@id/chart/add', array('Controller', 'add_chart'));

Flight::start();


class Controller {

    public static function index() {

        $id = Flight::request()->query->id;
        $orderby = Flight::request()->query->orderby;
        $order = Flight::request()->query->order;

        $conf = Util::get_conf_data();
        if (!$id && is_array($conf)) {
            $tmp_conf = $conf;
            $first_conf = array_shift($tmp_conf);
            $id = $first_conf['id'];
            Flight::view()->set('stats_id', $id);
        }

        $render_data = array();
        foreach ($conf as $k => $data) {
            if ($data['id'] == $id) {

                $orderby_sql = '';
                if ($orderby && $data['field_names'][$orderby]) {
                    if (!in_array($order, array('DESC', 'ASC'))) {
                        $order = 'DESC';
                    }
                    $orderby_sql = ' ORDER BY ' . $orderby . ' ' . $order;
                }

                $links = $data['links'];
                $sql = $data['sql'] . $orderby_sql . ' LIMIT 1000';

                $field_names = $data['field_names'];

                $params = array();
                if ($data['query_string']) {
                    foreach ($data['query_string'] as $field) {
                        $params[':' . $field] = Flight::request()->query->$field;
                    }
                }

                // data
                $db = Flight::db();
                $sth = $db->prepare($sql);
                $sth->execute($params);
                $rows = $sth->fetchAll();

                // charts
                if ($data['charts'] && $rows) {
                    $chart_data = array();
                    foreach ($data['charts'] as $k => $chart_conf) {
                        $chart_data[$k] = $chart_conf;
                        if ($chart_conf['x'] && $chart_conf['y']) {
                            foreach($rows as $row) {
                                $chart_data[$k]['data']['x'][] = $row[$chart_conf['x']];
                                $chart_data[$k]['data']['y'][] = $row[$chart_conf['y']];
                            }

                            $chart_data[$k]['data']['x'] = "'" . join("','", $chart_data[$k]['data']['x']) . "'";
                            $chart_data[$k]['data']['y'] = "'" . join("','", $chart_data[$k]['data']['y']) . "'";
                        }
                    }

                    $render_data['chart_data'] = $chart_data;
                    // var_dump($chart_data);
                }

                // render
                $render_data['field_names'] = $field_names;
                $render_data['sql'] = $sql;
                $render_data['links'] = $links;
                $render_data['rows'] = $rows;
                $render_data['conf'] = $data;

                break;
            }
        }

        Util::render_frame();
        Flight::render('index', $render_data, 'body_content');
        Flight::render('layout');
    }

    public static function add_dashboard() {
        echo 'Dashboard add ';

        /*
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

        Util::save_conf_data($data);
         */
        Flight::redirect('/');
    }

    public static function add_chart($name) {
        echo 'Dashboard chart add ' . $name;
    }
}

class Util {

    public static function render_frame($title = '') {

        $sidebar = Util::get_sidebar();
        Flight::render('header', array('title' => $title), 'header_content');
        Flight::render('navbar', array(), 'navbar_content');
        Flight::render('sidebar', array('sidebar' => $sidebar), 'sidebar_content');
    }

    public static function get_sidebar() {

        $data = self::get_conf_data();
        /*
        $data['default'] = array(
                                 'id' => 'addDashboard',
                                 'name' => '++ 添加',
                                 );

         */

        return $data;
    }

    public static function save_conf_data($data) {

        $path = '../data/data.php';

        $conf = self::get_conf_data();
        if (!$conf) {
            $conf = $data;
        } else {
            $conf = array_merge($conf, $data);
        }

        file_put_contents($path, json_encode($conf));
    }

    public static function get_conf_data() {

        /*
        $path = '../data/data.php';

        $conf = json_decode(file_get_contents($path), true);
        if (!$conf) {
            return array();
        }
         */

        $conf = include('../data/config.php');
        return $conf;
    }
}

