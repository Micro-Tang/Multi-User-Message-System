<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/12 上午10:03
 */

//防止恶意调用
if (!defined('TK')) {
    exit("非法调用");
}

//数据库连接
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', 'tk005918.');
define('DB_NAME', 'guestbook2');


//设置ROOT路径常量
define('ROOT',substr(dirname(__FILE__), 0, -7));

//版本过低退出
if (PHP_VERSION < '4.1.0') {
    exit('PHP版本过低');
}

//引入公共函数库
require_once (ROOT.'include/func.inc.php');
require_once (ROOT.'include/mysql.func.php');

//连接数据库
_connect_db();

//短信提醒
if (@$_COOKIE['username']) {
    $result = $conn->query("select count(tg_id) from tg_message where tg_touser='{$_COOKIE['username']}' and tg_state=0");
    $message = $result->fetch_array();
    if (empty($message[0])) {
        $message_display = '<strong>('.(0).')</strong>';
    } else {
        $message_display = '<strong>('.$message[0].')</strong>';
    }
}