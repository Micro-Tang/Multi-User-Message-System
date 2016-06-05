<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/12 上午11:01
 */
session_start();
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//登录状态检测
_login_state();
if (@$_GET['action'] == 'register') {
    if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
    {
        _alert_back('验证码不正确');
    }
        //创建数组接受post数据
        $register_post_array = array();
        if (!get_magic_quotes_gpc()) {
            if ($_POST['uniqid'] != $_SESSION['uniqid']) {
                exit('<script>alert("唯一标识符异常")</script>');
            }
            $register_post_array['uniqid'] = mysqli_real_escape_string($conn, $_POST['uniqid']);
            $register_post_array['active'] = sha1(uniqid(rand(), true));
            $register_post_array['username'] = mysqli_real_escape_string($conn, trim($_POST['username']));
            $register_post_array['password'] = sha1($_POST['password']);
            $register_post_array['question'] = mysqli_real_escape_string($conn, trim($_POST['passt']));
            $register_post_array['answer'] = sha1(trim($_POST['passd']));
            $register_post_array['sex'] = $_POST['sex'];
            $register_post_array['face'] = mysqli_real_escape_string($conn, $_POST['face']);
            $register_post_array['email'] = mysqli_real_escape_string($conn, trim($_POST['email']));
            $register_post_array['qq'] = (!empty($_POST['qq'])) ? mysqli_real_escape_string($conn, trim($_POST['qq'])) : null;
            $register_post_array['url'] = (!empty($_POST['url'])) ? mysqli_real_escape_string($conn, trim($_POST['url'])) : null;

            //插入之前判断用户名是否重复
            $sql = "select tg_username from tg_user where tg_username='{$register_post_array['username']}'";
            $result = _query($sql);
            if ($result->fetch_assoc()) {
                echo "<script>alert('此用户名已存在,请重新输入');history.back()</script>";
                exit();
            }

        //导入表单数据进入数据库
        $sql = "insert into tg_user(
                                      tg_uniqid,
                                      tg_active,
                                      tg_username,
                                      tg_password,
                                      tg_question,
                                      tg_answer,
                                      tg_sex,
                                      tg_face,
                                      tg_email,
                                      tg_qq,
                                      tg_url,
                                      tg_reg_time,
                                      tg_last_time,
                                      tg_last_ip
                                      ) 
                              values (
                                      '{$register_post_array['uniqid']}',
                                      '{$register_post_array['active']}',
                                      '{$register_post_array['username']}',
                                      '{$register_post_array['password']}',
                                      '{$register_post_array['question']}',
                                      '{$register_post_array['answer']}',
                                      '{$register_post_array['sex']}',
                                      '{$register_post_array['face']}',
                                      '{$register_post_array['email']}',
                                      '{$register_post_array['qq']}',
                                      '{$register_post_array['url']}',
                                      now(),
                                      now(),
                                      '{$_SERVER['REMOTE_ADDR']}'
                                      )";
        //插入数据
        $conn->query($sql);

        //跳转页面
        if (mysqli_affected_rows($conn) == 1) {
            //获取插入的数据的id
            $register_post_array['id'] = $conn->insert_id;
            //注册成功
            //关闭数据库
            $conn->close();
            //生成XML
            _set_xml("new.xml", $register_post_array);
            _location("注册成功", "active.php?active=".$register_post_array['active']);
        } else {
            //关闭数据库
            $conn->close();
            _location("注册失败,请重新注册", "register.php");
        }
    }
} else {
    $_SESSION['uniqid'] = $_uniqid = sha1(uniqid(rand(), true));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>多用户留言系统--注册</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/register.css">
    <script src="script/validate_register_form_date.js"></script>
</head>
<body>
<?php
require_once (ROOT.'include/header.inc.php');
?>
<div id="register">
    <h2>新会员注册</h2>
    <form method="post" action="register.php?action=register" onsubmit="return validate_submit()">
        <input type="hidden" name="uniqid" value="<?php echo $_uniqid ?>">
        <dl>
            <dt>请认真填写以下内容</dt>
            <dd>
                用&ensp;户&ensp;名<input type="text" id="username" name="username" class="text" onkeyup="validate_username()" />
                <span id="ms_username">(* 必填,由中文英文和数字组成,至少两位)</span>
            </dd>
            <dd>
                密&emsp;&emsp;码<input type="password" id="password" name="password" class="text" onkeyup="validate_password()"/>
                <span id="ms_password">(* 必填,由英文数字和点组成,至少六位)</span>
            </dd>
            <dd>
                确认密码<input type="password" id="password2" name="password2" class="text" onkeyup="validate_password2()"/>
                <span id="ms_password2">(* 必填,必须两次密码一致)</span>
            </dd>
            <dd>
                密码提示<input type="text" id="passt" name="passt" class="text" onkeyup="validate_passt()"/>
                <span id="ms_passt">(* 必填)</span>
            </dd>
            <dd>
                问题答案<input type="text" id="passd" name="passd" class="text" onkeyup="validate_passd()"/>
                <span id="ms_passd">(* 必填)</span>
            </dd>
            <dd>
                性&emsp;&emsp;别 男 <input type="radio" name="sex" value="男"checked="checked"/>
                女 <input type="radio" name="sex" value="女"/>
            </dd>
            <dd>
                头像选择
                <select name="face" onchange="document.images['face'].src=options[selectedIndex].value">
                    <option value="./images/1.gif" selected="selected">头像1</option>
                    <option value="./images/2.gif">头像2</option>
                    <option value="./images/3.gif">头像3</option>
                    <option value="./images/4.gif">头像4</option>
                    <option value="./images/5.gif">头像5</option>
                    <option value="./images/6.gif">头像6</option>
                    <option value="./images/7.gif">头像7</option>
                    <option value="./images/8.gif">头像8</option>
                    <option value="./images/9.gif">头像9</option>
                    <option value="./images/10.gif">头像10</option>
                    <option value="./images/11.gif">头像11</option>
                    <option value="./images/12.gif">头像12</option>
                    <option value="./images/13.gif">头像13</option>
                    <option value="./images/14.gif">头像14</option>
                </select>
                <img id="face" class="face" src="./images/1.gif" alt="头像选择" />
            </dd>
            <dd>
                电子邮箱<input type="text" id="email" name="email" class="text" onkeyup="validate_email()"/>
                <span id="ms_email">(*必填,用于用户激活)</span>
            </dd>
            <dd>
                Q&emsp;&emsp;&ensp;Q<input type="text" id="qq" name="qq" class="text" onkeyup="validate_qq()"/>
                <span id="ms_qq"></span>
            </dd>
            <dd>
                主页地址<input type="text" id="url" name="url" class="text" onkeyup="validate_url()"/>
                <span id="ms_url"></span>
            </dd>
            <dd>
                验&ensp;证&ensp;码<input type="text" id="varify" name="varify" class="text yzm"/>
                <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
            </dd>
            <dd class="button">
                <input type="submit" name="submit" value="注册""/>
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

