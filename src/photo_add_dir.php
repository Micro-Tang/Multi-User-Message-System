<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/18 下午10:18
 */
session_start();
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//非管理员不能进入
if (!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
    _alert_back("非管理员,不能进入");
}

if (@$_GET['action'] == 'adddir') {
    //接受数据
    $clean = array();
    $clean['name'] = mysqli_real_escape_string($conn, $_POST['name']);
    $clean['type'] = mysqli_real_escape_string($conn, $_POST['type']);
    $clean['password'] = mysqli_real_escape_string($conn, sha1($_POST['password']));
    $clean['content'] = mysqli_real_escape_string($conn, $_POST['content']);
    $clean['dir'] = time();

    //检查存放相册的主目录是否存在
    if (!is_dir('./photo')) {
        mkdir('photo', 0777);
        chmod('photo',0777);
    }
    //在主目录里再创建一个以当前时间戳命名的文件夹
    if (!is_dir('./photo/'.$clean['dir'])) {
        mkdir('./photo/'.$clean['dir'], 0777);
        chmod('./photo/'.$clean['dir'],0777);
    }

    //写入数据到数据库
    if (empty($_POST['type'])) {
        _query("insert into tg_dir(
                                  tg_name,
                                  tg_type,
                                  tg_content,
                                  tg_dir,
                                  tg_date
                                  ) 
                        values (
                        '{$clean['name']}',
                        '{$clean['type']}',
                        '{$clean['content']}',
                        'photo/{$clean['dir']}',
                        now()
                                )
               ");

        //跳转页面
        if (mysqli_affected_rows($conn) == 1) {
            //关闭数据库
            $conn->close();
            _location("创建相册成功", "photo.php");
        } else {
            //关闭数据库
            $conn->close();
            _location("创建相册失败,请重新创建", "photo_add_dir.php");
        }
    } else {
        _query("insert into tg_dir(
                                  tg_name,
                                  tg_type,
                                  tg_password,
                                  tg_content,
                                  tg_dir,
                                  tg_date
                                  ) 
                        values (
                        '{$clean['name']}',
                        '{$clean['type']}',
                        '{$clean['password']}',
                        '{$clean['content']}',
                        'photo/{$clean['dir']}',
                        now()
                                )
               ");

        //跳转页面
        if (mysqli_affected_rows($conn) == 1) {
            //关闭数据库
            $conn->close();
            _location("创建相册成功", "photo.php");
        } else {
            //关闭数据库
            $conn->close();
            _location("创建相册失败,请重新创建", "photo_add_dir.php");
        }
    }
}
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--添加相册</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/photo_add_dir.css">
        <script src="script/photo_add_dir.js"></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="photo">
        <h2>添加相册</h2>
        <form method="post" action="?action=adddir">
            <dl>
                <dd>相册名称:&nbsp;<input type="text" name="name" class="text"/></dd>
                <dd>相册类型:
                    &nbsp;<label for="public">公开</label>&nbsp;<input checked="checked" type="radio" id="public" name="type" value="0"/>&emsp;
                    <label for="private">私密</label>&nbsp;<input id="private" type="radio" name="type" value="1"/></dd>
                <dd id="pass">相册密码:&nbsp;<input type="password" name="password" class="text"/></dd>
                <dd>相册简介:&nbsp;<textarea name="content"></textarea></dd>
                <dd><input type="submit" class="submit" value="新增相册"></dd>
            </dl>
        </form>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>