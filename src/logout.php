<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/18 下午9:57
 */
session_start();
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
_unsetCookies();