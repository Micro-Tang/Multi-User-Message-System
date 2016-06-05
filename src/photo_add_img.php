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
//游客不能进入
if (!(isset($_COOKIE['username']))) {
    _alert_back("游客,不能进入");
}
//保存图片录入数据库
if (@$_GET['action'] == 'addimg') {
    //接收数据
    $_clean = array();
    $_clean['name'] = mysqli_real_escape_string($conn, $_POST['name']);
    $_clean['url'] = mysqli_real_escape_string($conn, $_POST['url']);
    $_clean['content'] = mysqli_real_escape_string($conn, $_POST['content']);
    $_clean['sid'] = mysqli_real_escape_string($conn, $_POST['sid']);

    //写入数据
    _query("insert into tg_photo (
                                  tg_name,
                                  tg_url,
                                  tg_content,
                                  tg_sid,
                                  tg_username,
                                  tg_date
                                  ) 
                          values (
                                  '{$_clean['name']}',
                                  '{$_clean['url']}',
                                  '{$_clean['content']}',
                                  '{$_clean['sid']}',
                                  '{$_COOKIE['username']}',
                                  now()
                                  )");

    //跳转页面
    if (mysqli_affected_rows($conn) == 1) {
        //关闭数据库
        $conn->close();
        _location("图片添加成功", "photo_show.php?id=".$_clean['sid']);
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("图片添加失败");
    }
}

if (isset($_GET['id'])) {
    if (!!$rows = _query("select tg_id, tg_dir from tg_dir where tg_id='{$_GET['id']}'")->fetch_assoc()) {
        $_html = array();
        $_html['id'] = $rows['tg_id'];
        $_html['dir'] = $rows['tg_dir'];
    } else {
        _alert_back("不存在此相册");
    }
} else {
    _alert_back("非法操作");
}
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--添加相册</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/photo_add_img.css">
        <script src="script/photo_add_img.js"></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="photo">
        <h2>上传图片</h2>
        <form method="post" action="?action=addimg">
            <input type="hidden" name="sid" value="<?php echo $_html['id'] ?>" />
            <dl>
                <dd>图片名称:&nbsp;<input type="text" name="name" class="text"/></dd>
                <dd>图片地址:
                    <input type="text" id="url" name="url" readonly="readonly" class="text" />
                    <a href="javascript:;" title="<?php echo $_html['dir'] ?>" id="up">上传</a>
                </dd>
                <dd>图片简介:&nbsp;<textarea name="content"></textarea></dd>
                <dd><input type="submit" class="submit" value="上传图片"></dd>
            </dl>
        </form>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>