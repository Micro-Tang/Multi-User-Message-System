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
//上传
if (@$_GET['action'] == 'up') {
    $MAX_FILE_SIZW = $_POST['MAX_FILE_SIZW'];

    //设置上传图片类型
    $files = array('image/jpeg', 'image/pjpeg', 'image/png', 'image/jpg', 'image/gif');
    if (is_array($files)) {
        if (!in_array($_FILES['userfile']['type'], $files)) {
            _alert_back("上传图片类型错误");
        }
    }

    //文件错误类型
    if ($_FILES['userfile']['error'] > 0) {
        switch($_FILES['userfile']['error']) {
            case 1: _alert_back("上传文件超过约定值1"); break;
            case 2: _alert_back("上传文件超过约定值2"); break;
            case 3: _alert_back("文件部分被上传"); break;
            case 4: _alert_back("没有任何文件被上传"); break;
        }
        exit();
    }

    //判断配置大小
    if ($_FILES['userfile']['size'] > $MAX_FILE_SIZW) {
        _alert_back("上传文件大小不得超过1M");
    }

    //获取文件后缀
    $_file_extend_name = explode('.', $_FILES['userfile']['name']);
    $_uploaded_name = $_POST['dir'].'/'.time().'.'.$_file_extend_name[1];

    //移动文件
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        if (!@move_uploaded_file($_FILES['userfile']['tmp_name'], $_uploaded_name)) {
            _alert_back("文件移动失败");
        } else {
            chmod($_uploaded_name,0777);
            echo "<script>
                    window.opener.document.getElementById('url').value='".$_uploaded_name."';
                    window.close();
                  </script>";
        }
    } else {
        _alert_back("上传的临时文件丢失");
    }
}

//接收dir
if (!isset($_GET['dir'])) {
    _alert_back("非法操作");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>多用户留言系统--添加相册</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
</head>
<body>
    <div id="upimg" style="padding: 20px;">
        <form enctype="multipart/form-data" action="upimg.php?action=up" method="post">
            <input type="hidden" name="MAX_FILE_SIZW" value="800000" />
            <input type="hidden" name="dir" value="<?php echo $_GET['dir'] ?>" />
            选择图片:<input type="file" name="userfile" />
            <input type="submit" value="上传" />
        </form>
    </div>
</body>
</html>
