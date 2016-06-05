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
//游客无法进入
if(!@$_COOKIE['username']) {
    _location("请先登录", "login.php");
}

//写数据
if (@$_GET['action'] === 'modify') {
    if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
    {
        _alert_back('验证码不正确');
    }
    //接收post数据
    $clean = array();
    $clean['type'] = mysqli_real_escape_string($conn, $_POST['type']);
    $clean['title'] = mysqli_real_escape_string($conn, _check_post_title($_POST['title'], 2, 40));
    $clean['content'] = mysqli_real_escape_string($conn, _check_post_content($_POST['content'], 10));
    $clean['id'] = mysqli_real_escape_string($conn, $_POST['id']);

        //写入数据库
    $conn->query("update tg_article set tg_type='{$clean['type']}',
                                        tg_title='{$clean['title']}',
                                        tg_content='{$clean['content']}'
                                    where tg_id='{$clean['id']}'");

    //跳转页面
    if (mysqli_affected_rows($conn) == 1) {
        //注册成功
        //关闭数据库
        $conn->close();
        _location("修改帖子成功", "article.php?id=".$clean['id'] );
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("帖子没有任何更改");
    }
}

if (@isset($_GET['id'])) {
    $result = _query("select * from tg_article where tg_reid=0 and tg_id='{$_GET['id']}'");
    if (!!!$rows = $result->fetch_assoc()) {
        _alert_back("不存在此帖子");
    }
    if ($rows['tg_username'] !== $_COOKIE['username']) {
        _alert_back("你不能修改此数据");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>多用户留言系统--修改帖子</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/article_modify.css">
    <script src="script/post.js"></script>
</head>
<body>
<?php
require_once (ROOT.'include/header.inc.php');
?>
<div id="article_modify">
    <h2>修改帖子</h2>
    <form method="post" action="?action=modify">
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
        <dl>
            <dt>请认真填写以下内容</dt>
            <dd>
                类&emsp;&emsp;型:
                <?php
                    foreach(range(1, 16) as $num) {
                        if ($rows['tg_type'] == $num) {
                            echo "&ensp;<label for='type" . $num . "'><input checked='checked' type='radio' id=type" . $num . " name='type' value='" . $num . "'/>&ensp;";
                        } else {
                            echo "&ensp;<label for='type" . $num . "'><input type='radio' id=type" . $num . " name='type' value='" . $num . "'/>&ensp;";
                        }
                        echo "<img src='images/icon".$num.".png' /></label>";
                        if ($num === 8) {
                            echo "<br />&emsp;&emsp;&emsp;&emsp;&ensp;";
                        }
                    }
                ?>
            </dd>
            <dd>
                标&emsp;&emsp;题: <input type="text" id="title" name="title" class="text" value="<?php echo $rows['tg_title'] ?>"/>(*必填, 2-40位)
            </dd>
            <dd>
                <?php require_once 'include/ubb.php'; ?>
                <textarea name="content" rows="9"><?php echo $rows['tg_content'] ?></textarea>
            </dd>
            <dd>
                验&ensp;证&ensp;码: <input type="text" id="varify" name="varify" class="text yzm"/>
                <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
                <input type="submit" class="button" name="submit" value="发帖""/>
            </dd>
        </dl>
    </form>
</div>
<?php
require_once (ROOT.'include/footer.inc.php');
?>
</body>
</html>

