<?php
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//缩略图
_thumb(@$_GET['filename'], @$_GET['percent']);