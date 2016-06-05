<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/15 下午9:56
 */

//防止恶意调用
if (!defined('TK')) {
    exit("非法调用");
}

/**
 * _connect_db() {连接数据库}
 * @access public
 * @return void
 */
function _connect_db() {
    global $conn;
    //连接数据库
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PWD, DB_NAME) or die('数据库连接失败');
    //设置字符集
    $conn->query("SET NAMES UTF8") or die("设置字符集失败");
}

/**
 * _query() {执行数据库查询语句}
 * @param $sql
 * @return bool|mysqli_result
 */
function _query($sql) {
    global $conn;
    $result = $conn->query($sql) or die($conn->error);
    return $result;
}