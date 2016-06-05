<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/13 下午9:51
 */
define('TK', true);
require_once ('./include/common.inc.php');

//创建验证码
session_start();
_code(4);