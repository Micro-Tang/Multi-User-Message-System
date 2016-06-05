<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/12 上午9:43
 */

//防止恶意调用
if (!defined('TK')) {
    exit("非法调用");
}
//关闭数据库
$conn->close();
//程序结束时间
$_end_time = _time();
$_count_time = _execute_time($_start_time, $_end_time);
?>
<div id="footer">
    <p>版权所有 翻版必究</p>
    <p>本程序由<span>唐堃</span>个人完成</p>
    <p>程序执行耗时 <span><?php echo $_count_time ?></span> 秒</p>
</div>