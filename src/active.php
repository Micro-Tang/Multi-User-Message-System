<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/15 下午11:24
 */
session_start();
define('TK', true);
require_once ('./include/common.inc.php');

if (!isset($_GET['active'])) {
    _alert_back('非法操作');
}

//开始激活处理
if (isset($_GET['action']) && isset($_GET['active']) && $_GET['action'] == 'ok') {
    $active = mysqli_real_escape_string($conn, $_GET['active']);
    _query("select tg_active from tg_user where tg_active='$active' limit 1");
    if (mysqli_affected_rows($conn)) {
        //激活成功,清空数据库内active
        _query("update tg_user set tg_active=''");
        if (mysqli_affected_rows($conn) == 1) {
            $conn->close();
            _location('账户激活成功', 'login.php');
        } else {
            $conn->close();
            _location('账户激活失败', 'register.php');
        }
    } else {
        _alert_back('非法操作');
    }
}
?>
<DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>多用户留言系统--激活账户</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/active.css">
</head>
<body>
<?php require_once (ROOT.'include/header.inc.php')?>
<div id="active">
    <h2>激活用户</h2>
    <p>本页为了模拟你的邮件功能,点击以下超链接激活你的用户</p>
    <p><a href="active.php?action=ok&active=<?php echo $_GET['active'] ?>">
            <?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] ?>active.php?action=ok&active=<?php echo $_GET['active'] ?>
        </a></p>
</div>
<?php
require_once (ROOT.'include/footer.inc.php');
?>
</body>
</html>
