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

//添加好友
if (@$_GET['action'] == 'add') {
    if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
    {
        _alert_back('验证码不正确');
    }
    if (mb_strlen($_POST['content'], 'utf-8') < 5 || mb_strlen($_POST['content'], 'utf-8') > 200) {
        _alert_back("发信内容应在5位在200位之间");
    }
    $friend_array = array();
    $friend_array['touser'] = mysqli_real_escape_string($conn, $_POST['touser']);
    $friend_array['fromuser'] = mysqli_real_escape_string($conn, $_COOKIE['username']);
    $friend_array['content'] = mysqli_real_escape_string($conn, $_POST['content']);
    //验证是否自己添加自己
    if ($friend_array['touser'] === $friend_array['fromuser']) {
        _alert_close("不能添加自己作为好友!");
    }

    //验证是否已经是好友
    $result = $conn->query("select tg_id 
                                    from tg_friend 
                                    where 
                                    (tg_fromuser='{$friend_array['fromuser']}' and tg_touser='{$friend_array['touser']}')
                                    OR 
                                    (tg_fromuser='{$friend_array['touser']}' and tg_touser='{$friend_array['fromuser']}')");
    $rows = $result->fetch_row();
    if (!empty($rows)) {
        _alert_close("你们已经是好友或者是未验证的好友!");
    }

    //插入数据库
    _query("insert into tg_friend(tg_touser, tg_fromuser, tg_content, tg_date)
                                   VALUES 
                                   ('{$friend_array['touser']}', '{$friend_array['fromuser']}',
                                   '{$friend_array['content']}',
                                   now())");

    //跳转页面
    if (mysqli_affected_rows($conn) == 1) {
        //插入成功
        //关闭数据库
        $conn->close();
        session_destroy();
        _alert_close("添加成功");
    } else {
        //关闭数据库
        $conn->close();
        session_destroy();
        _alert_back("添加失败");
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
    <title>多用户留言系统--添加好友</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/friend.css" />
</head>
<body>
<div id="friend">
    <h3>添加好友</h3>
    <form action="?action=add" method="post">
        <input type="hidden" name="touser" value="<?php echo $result['tg_username'] ?>" />
        <dl>
            <dd>
                <input type="text" class="text" readonly="readonly" value="TO: <?php echo $result['tg_username'] ?>"/>
            </dd>
            <dd>
                <textarea name="content">你好我是 <?php echo $_COOKIE['username'] ?>, 我想和你交朋友。</textarea>
            </dd>
            <dd>
                验证码: <input type="text" id="varify" name="varify" class="text yzm"/>
                <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
                <input type="submit" class="submit" name="submit" value="添加" />
            </dd>
        </dl>
    </form>
</div>
</body>
</html>