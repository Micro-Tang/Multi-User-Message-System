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
//修改数据
if ($_GET) {
    if ($_GET['action'] == 'modify') {
        if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
        {
            _alert_back('验证码不正确');
        }
        //创建数组接受post数据
        $member_modify_post_array = array();
        if (!get_magic_quotes_gpc()) {
            $member_modify_post_array['username'] = mysqli_real_escape_string($conn, trim($_POST['username']));
            $member_modify_post_array['password'] = (!empty($_POST['password'])) ? sha1($_POST['password']) : '';
            $member_modify_post_array['sex'] = $_POST['sex'];
            $member_modify_post_array['face'] = mysqli_real_escape_string($conn, $_POST['face']);
            $member_modify_post_array['email'] = mysqli_real_escape_string($conn, trim($_POST['email']));
            $member_modify_post_array['qq'] = (!empty($_POST['qq'])) ? mysqli_real_escape_string($conn, trim($_POST['qq'])) : null;
            $member_modify_post_array['url'] = (!empty($_POST['url'])) ? mysqli_real_escape_string($conn, trim($_POST['url'])) : null;
            $member_modify_post_array['switch'] = mysqli_real_escape_string($conn, $_POST['switch']);
            $member_modify_post_array['autographp'] = mysqli_real_escape_string($conn, $_POST['autograph']);

            //修改之前判断用户名是否重复
            if (!$member_modify_post_array['username'] == $_COOKIE['username']) {
                $sql = "select tg_username from tg_user where tg_username='{$member_modify_post_array['username']}'";
                $result = _query($sql);
                if ($result->fetch_assoc()) {
                    _alert_back('此用户名已存在,请重新输入');
                }
            }

            //修改数据库数据
            if ($member_modify_post_array['password']) {
                $sql = "update tg_user set tg_username='{$member_modify_post_array['username']}',
                                   tg_password='{$member_modify_post_array['password']}',
                                   tg_sex='{$member_modify_post_array['sex']}',
                                   tg_face='{$member_modify_post_array['face']}',
                                   tg_email='{$member_modify_post_array['email']}',
                                   tg_qq='{$member_modify_post_array['qq']}',
                                   tg_url='{$member_modify_post_array['url']}',
                                   tg_switch='{$member_modify_post_array['switch']}',
                                   tg_autograph='{$member_modify_post_array['autograph']}'
                                   where tg_username='" . $_COOKIE['username'] . "'";
            } elseif ($member_modify_post_array['password'] == '') {
                $sql = "update tg_user set tg_username='{$member_modify_post_array['username']}',
                                   tg_sex='{$member_modify_post_array['sex']}',
                                   tg_face='{$member_modify_post_array['face']}',
                                   tg_email='{$member_modify_post_array['email']}',
                                   tg_qq='{$member_modify_post_array['qq']}',
                                   tg_url='{$member_modify_post_array['url']}',
                                   tg_switch='{$member_modify_post_array['switch']}',
                                   tg_autograph='{$member_modify_post_array['autographp']}'
                                   where tg_username='" . $_COOKIE['username'] . "'";
            }
            //修改数据
            $conn->query($sql);

            //跳转页面
            if (mysqli_affected_rows($conn) == 1) {
                //修改成功
                //关闭数据库
                $conn->close();
                session_destroy();
                _location("修改成功", "member.php");
            } else {
                //关闭数据库
                $conn->close();
                session_destroy();
                _location("数据没有被修改", "member_modify.php");
            }
        }
        }
}

//防止游客进入本页面
if (!isset($_COOKIE['username'])) {
    _alert_back('游客不能进入本页面');
} else {
    $rows = _query("select * from tg_user where tg_username='{$_COOKIE['username']}'");
    $result = $rows->fetch_assoc();
}
?>
<DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>多用户留言系统--修改资料</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/member_modify.css">
    <script src="script/validate_member_modify_form_date.js"></script>
</head>
<body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="member">
        <?php require_once (ROOT.'include/member.inc.php')?>
        <div id="member_main">
            <h2>会员管理中心</h2>
            <form method="post" action="member_modify.php?action=modify" onsubmit="return validate_submit()">
                <dl>
                    <dd>
                        用&ensp;户&ensp;名:
                        <input type="text" id="username" class="text" name="username" value="<?php echo _html($result['tg_username']); ?>" onkeyup="validate_username()"/>
                        <span id="ms_username"></span>
                    </dd>
                    <dd>
                        密&emsp;&emsp;码: <input type="password" id="password" name="password" class="text" onkeyup="validate_password()"/>
                        <span id="ms_password">(空为不修改)</span>
                    </dd>
                    <dd>
                        确认密码: <input type="password" id="password2" name="password2" class="text" onkeyup="validate_password2()"/>
                        <span id="ms_password2"></span>
                    </dd>
                    <dd>
                        性&emsp;&emsp;别:
                        <?php
                        if (_html($result['tg_sex']) == '男') {
                            echo "<input type='radio' name='sex' value='男' checked='checked' /> 男 ";
                            echo "<input type='radio' name='sex' value='女' /> 女";
                        } elseif (_html($result['tg_sex']) == '女') {
                            echo "<input type='radio' name='sex' value='男' /> 男 ";
                            echo "<input type='radio' name='sex' value='女' checked='checked' /> 女";
                        }

                        if($result['tg_switch'] == 1) {
                            //启用签名
                            $switch = '<input type="radio" name="switch" value="1" checked="checked" />启用签名
                                <input type="radio" name="switch" value="0"/>禁用签名';
                        } elseif ($result['tg_switch'] == 0) {
                            //禁用签名
                            $switch = '<input type="radio" name="switch" value="1" />启用签名
                                <input type="radio" name="switch" value="0" checked="checked" />禁用签名';
                        }
                        ?>
                    </dd>
                    <dd>
                        头&emsp;&emsp;像:
                        <img name="face" src="<?php echo _html($result['tg_face']); ?>" alt="头像" />
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
                    </dd>
                    <dd>
                        电子邮箱: <input type="text" id="email" class="text" name="email" value="<?php echo _html($result['tg_email']); ?>" onkeyup="validate_email()"/>
                        <span id="ms_email"></span>
                    </dd>
                    <dd>
                        主&emsp;&emsp;页: <input type="text" id="url" class="text" name="url" value="<?php echo _html($result['tg_url']); ?>" onkeyup="validate_url()"/>
                        <span id="ms_url"></span>
                    </dd>
                    <dd>
                        Q&emsp;&emsp;&ensp;Q: <input type="text" id="qq" class="text" name="qq" value="<?php echo _html($result['tg_qq']); ?>" onkeyup="validate_qq()"/>
                        <span id="ms_qq"></span>
                    </dd>
                    <dd>
                        个性签名: <?php echo $switch ?>
                                <p><textarea name="autograph"><?php echo $result['tg_autograph'] ?></textarea></p>
                    </dd>
                    <dd>
                        验&ensp;证&ensp;码: <input type="text" id="varify" name="varify" class="text yzm"/>
                        <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
                        <input class="submit" type="submit" name="submit" value="修改""/>
                    </dd>
                </dl>
            </form>
        </div>
    </div>
    <?php
    $rows->free_result();
    require_once (ROOT.'include/footer.inc.php')
    ?>
</body>
</html>