<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/21 上午10:35
 */
//防止恶意调用
if (!defined('TK')) {
    exit("非法调用");
}
?>
<div id="member_sidebar">
    <h2>管理导航</h2>
    <dl>
        <dt>系统管理</dt>
        <dd><a href="manage.php">后台首页</a></dd>
        <dd><a href="manage_set.php">系统设置</a></dd>
    </dl>
</div>
