<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/16 下午3:57
 */
session_start();

//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//登录状态检测
_login_state();

if (@$_GET['action'] == 'login') {
    if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
    {
        _alert_back('验证码不正确');
    }
    //创建数组接受post数据
    $register_post_array = array();
    if (!get_magic_quotes_gpc()) {
        $register_post_array['username'] = mysqli_real_escape_string($conn, trim($_POST['username']));
        $register_post_array['password'] = sha1($_POST['password']);
        $register_post_array['time'] = mysqli_real_escape_string($conn, $_POST['time']);

        //数据库查询
        $sql = "select * from tg_user where tg_username='{$register_post_array['username']}'
        and tg_password='{$register_post_array['password']}' and tg_active=''";
        $res = $conn->query($sql);
        if(!!$rows = $res->fetch_assoc()) {
            //登录成功后记录登录信息
            if ($rows['tg_level'] == 1) {
                $_SESSION['admin'] = $rows['tg_username'];
            }
            _setCookies($rows['tg_username'], $register_post_array['time']);
            _query("update tg_user set 
                                      tg_last_time=now(), 
                                      tg_last_ip='{$_SERVER['REMOTE_ADDR']}', 
                                      tg_login_count=(tg_login_count+1)
                                      where tg_username='{$register_post_array['username']}'");
            header("Location:member.php");
        } else {
            $conn->close();
            _location("用户名或密码错误", "login.php");
        }
        $res->free_result();
    }
}
?>
<DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>多用户留言系统--登录</title>
<link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
<link rel="stylesheet" type="text/css" href="./styles/1/login.css">
<script src="script/validate_login_form_date.js"></script>
</head>
<body>
<?php require_once (ROOT.'include/header.inc.php')?>
<div id="login">
    <h2>登录</h2>
    <form method="post" action="login.php?action=login" onsubmit="return validate_submit()">
        <dl>
            <dd>
                用&ensp;户&ensp;名: <input type="text" id="username" name="username" class="text" onkeyup="validate_username()"/>
                <span id="ms_username">(请填写用户名)</span>
            </dd>
            <dd>
                密&emsp;&emsp;码: <input type="password" id="password" name="password" class="text" onkeyup="validate_password()"/>
                <span id="ms_password">(请填写密码)</span>
            </dd>
            <dd>
                保持登录: <input type="radio" name="time" value="0" checked="checked" />&ensp;不保留&ensp;
                <input type="radio" name="time" value="1" />&ensp;一天&ensp;
                <input type="radio" name="time" value="2" />&ensp;一周&ensp;
                <input type="radio" name="time" value="3" />&ensp;一月&ensp;
            </dd>
            <dd>
                验&ensp;证&ensp;码: <input type="text" id="varify" name="varify" class="text yzm"/>
                <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
            </dd>
            <dd class="button">
                <input type="submit" name="submit" value="登录""/>
                <input type="reset" name="reset" value="重置"/>
            </dd>
        </dl>
    </form>
</div>
<?php
require_once (ROOT.'include/footer.inc.php');
?>
</body>
</html>