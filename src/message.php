<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/23 上午11:05
 */
session_start();
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
if (!isset($_COOKIE['username'])) {
    _alert_close('请先登录');
}

if (@$_GET['action'] == 'write') {
    if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
    {
        _alert_back('验证码不正确');
    }
    if (mb_strlen($_POST['content'], 'utf-8') < 5 || mb_strlen($_POST['content'], 'utf-8') > 200) {
        _alert_back("发信内容应在5位在200位之间");
    }
    $message_array = array();
    $message_array['touser'] = mysqli_real_escape_string($conn, $_POST['touser']);
    $message_array['fromuser'] = mysqli_real_escape_string($conn, $_COOKIE['username']);
    $message_array['content'] = mysqli_real_escape_string($conn, $_POST['content']);
    //插入数据库
    _query("insert into tg_message(tg_touser, tg_fromuser, tg_content, tg_date)
                                   VALUES 
                                   ('{$message_array['touser']}', '{$message_array['fromuser']}',
                                   '{$message_array['content']}',
                                   now())");
    
    //跳转页面
    if (mysqli_affected_rows($conn) == 1) {
        //插入成功
        //关闭数据库
        $conn->close();
        _alert_close("发信成功");
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("发送失败");
    }
}

if (isset($_GET['id'])) {
    $rows = _query("select tg_username from tg_user where tg_id='{$_GET['id']}'");
    if (!($result = $rows->fetch_assoc())) {
        _alert_close("从用户不存在");
    }
} else {
    _alert_back("非法操作");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>多用户留言系统--发消息</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/message.css" />
</head>
<body>
<div id="message">
    <h3>写短信</h3>
    <form action="?action=write" method="post">
        <input type="hidden" name="touser" value="<?php echo $result['tg_username'] ?>" />
        <dl>
            <dd>
                <input type="text" class="text" readonly="readonly" value="TO: <?php echo $result['tg_username'] ?>"/>
            </dd>
            <dd>
                <textarea name="content"></textarea>
            </dd>
            <dd>
                验证码: <input type="text" id="varify" name="varify" class="text yzm"/>
                <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
                <input type="submit" class="submit" name="submit" value="发送" />
            </dd>
        </dl>
    </form>
</div>
</body>
</html>