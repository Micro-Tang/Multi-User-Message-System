<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/12 上午9:37
 */
//程序开始时间
$_start_time = _time();

//防止恶意调用
if (!defined('TK')) {
    exit("非法调用");
}
?>
<div id="header">
    <a href="index.php"><img src="images/head_image.jpeg"/></a>
    <ul>
        <li><a href="index.php">首页</a></li>
        <?php
            if (isset($_COOKIE['username'])) {
                echo "<li><a href='member.php'>".$_COOKIE['username']." . 个人中心 </a><img src='images/unread.png' title='未读' /> <a href='member_message.php'>$message_display</a></li>";
            } else {
                echo "<li><a href='register.php'>注册</a></li>"."\n";
                echo "<li><a href='login.php'>登陆</a></li>"."\n";
            }
        ?>
        <li><a href="blog.php">博友</a></li>
        <li><a href="photo.php">相册</a></li>
        <li><a href="">分格</a></li>
        <?php
        if (isset($_COOKIE['username'])) {
            echo "<li><a href='logout.php'>退出</a></li>";
        }
        ?>
    </ul>
</div>