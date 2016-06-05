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

if (@$_GET['action'] == 'modify') {
    $clean = array();
    $clean['name'] = mysqli_real_escape_string($conn, $_POST['name']);
    $clean['type'] = mysqli_real_escape_string($conn, $_POST['type']);
    $clean['id'] = mysqli_real_escape_string($conn, $_POST['id']);
    if ($_POST['type'] == 1) {
        $clean['password'] = mysqli_real_escape_string($conn, sha1($_POST['password']));
    } else {
        $clean['password'] = '';
    }
    $clean['content'] = mysqli_real_escape_string($conn, $_POST['content']);

    //修改数据
    if (strlen($clean['password']) != 0) {
        _query("update tg_dir set
                              tg_name='{$clean['name']}',
                              tg_type='{$clean['type']}',
                              tg_password='{$clean['password']}',
                              tg_content='{$clean['content']}'
                            where
                              tg_id='{$clean['id']}'
          ");

        //跳转页面
        if (mysqli_affected_rows($conn) == 1) {
            //关闭数据库
            $conn->close();
            _location("修改相册成功", "photo.php");
        } else {
            //关闭数据库
            $conn->close();
            _alert_back("修改相册失败");
        }
    } elseif (strlen($clean['password']) == 0) {
        _query("update tg_dir set
                              tg_name='{$clean['name']}',
                              tg_type='{$clean['type']}',
                              tg_content='{$clean['content']}'
                            where
                              tg_id='{$clean['id']}'
          ");
        //跳转页面
        if (mysqli_affected_rows($conn) == 1) {
            //关闭数据库
            $conn->close();
            _location("修改相册成功", "photo.php");
        } else {
            //关闭数据库
            $conn->close();
            _alert_back("修改相册失败");
        }
    }
}

if (@isset($_GET['id'])) {
    //从数据库提取数据
    $result = _query("select tg_id, tg_name, tg_password, tg_type, tg_content from tg_dir where tg_id='{$_GET['id']}'");
    $rows = $result->fetch_assoc();
}
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--修改相册</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/photo_modify_dir.css">
        <script src="script/photo_modify_dir.js"></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="photo_modify_dir">
        <h2>修改相册</h2>
        <form method="post" action="?action=modify">
            <dl>
                <dd>相册名称:&nbsp;<input type="text" name="name" class="text" value="<?php echo $rows['tg_name'] ?>"/></dd>
                <dd>
                    相册类型:&nbsp;<label for="public">公开</label>&nbsp;
                    <input <?php if ($rows['tg_type'] == 0) echo 'checked="checked"' ?> type="radio" id="public" name="type" value="0"/>&emsp;
                    <label for="private">私密</label>&nbsp;
                    <input <?php if ($rows['tg_type'] == 1) echo 'checked="checked"' ?> id="private" type="radio" name="type" value="1"/>
                </dd>
                <dd id="pass" <?php if($rows['tg_type'] == 1) echo 'style="display: block;"'?>>相册密码:&nbsp;<input type="password" name="password" class="text" /></dd>
                <dd>相册简介:&nbsp;<textarea name="content"><?php echo $rows['tg_content'] ?></textarea></dd>
                <dd><input type="submit" class="submit" value="修改相册"></dd>
            </dl>
            <input type="hidden" name="id" value="<?php echo @$_GET['id'] ?>" />
        </form>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>