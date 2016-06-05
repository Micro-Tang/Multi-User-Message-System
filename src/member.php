<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/20 下午11:49
 */
session_start();
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//防止游客进入本页面
if (!isset($_COOKIE['username'])) {
    _alert_back('游客不能进入本页面');
} else {
    $rows = _query("select * from tg_user where tg_username='{$_COOKIE['username']}'");
    $result = $rows->fetch_assoc();
    //判断是否是管理员
    if ($result['tg_level'] == 0) {
        $level_str = "普通会员";
    } else if ($result['tg_level'] == 1) {
        $level_str = "管理员";
    }
}
?>
<DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>多用户留言系统--个人中心</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/member.css">
</head>
<body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="member">
        <?php require_once (ROOT.'include/member.inc.php')?>
        <div id="member_main">
            <h2>会员管理中心</h2>
            <dl>
                <dd>用&ensp;户&ensp;名: <?php echo _html($result['tg_username']); ?></dd>
                <dd>性&emsp;&emsp;别: <?php echo _html($result['tg_sex']); ?></dd>
                <dd>头&emsp;&emsp;像: <img src="<?php echo _html($result['tg_face']); ?>" alt="头像" /></dd>
                <dd>电子邮箱: <?php echo _html($result['tg_email']); ?></dd>
                <dd>主&emsp;&emsp;页: <?php echo _html($result['tg_url']); ?></dd>
                <dd>Q&emsp;&emsp;&ensp;Q: <?php echo _html($result['tg_qq']); ?></dd>
                <dd>注册时间 <?php echo _html($result['tg_reg_time']); ?></dd>
                <dd>身&emsp;&emsp;份: <?php echo $level_str; ?></dd>
            </dl>
        </div>
    </div>
    <?php
    $rows->free_result();
    require_once (ROOT.'include/footer.inc.php')
    ?>
</body>
</html>