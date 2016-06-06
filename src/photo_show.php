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

//删除图片
if (@$_GET['action'] == 'delete' && isset($_GET['id'])) {
    //取得当前图片信息
    if (!!$delete_arr = _query("select tg_sid, tg_id, tg_url, tg_username from tg_photo where tg_id='{$_GET['id']}'")->fetch_assoc()) {
        if ($delete_arr['tg_username'] == $_COOKIE['username'] || isset($_SESSION['admin'])) {
            //删除图片
            if ($conn->query("delete from tg_photo where tg_id='{$delete_arr['tg_id']}'")) {
                if (file_exists($delete_arr['tg_url'])) {
                    unlink($delete_arr['tg_url']);
                    header("Location: photo_show.php?id={$delete_arr['tg_sid']}");
                } else {
                    _alert_back($delete_arr['tg_url']."文件不存在");
                }
            } else {
                _alert_back("删除图片失败");
            }
        }
    } else {
        _alert_back("不存在此图片");
    }
}

if (isset($_GET['id'])) {
    if (!!$rows = _query("select tg_id, tg_type, tg_name from tg_dir where tg_id='{$_GET['id']}'")->fetch_assoc()) {
        $_html = array();
        $_html['id'] = $rows['tg_id'];
        $_html['name'] = $rows['tg_name'];
        $_html['type'] = $rows['tg_type'];

        //获取表单密码值
        if (isset($_POST['password'])) {
            $_password = sha1($_POST['password']);
            $_id = $_html['id'];
            if (_query("select tg_id from tg_dir where tg_id='$_id' and tg_password='$_password' limit 1")->fetch_assoc()) {
                setcookie('photo'.$_html['id'], $_html['name']);
                header("Location: photo_show.php?id={$_html['id']}");
            } else {
                _alert_back("密码错误");
            }
        }
    } else {
        _alert_back("不存在此相册");
    }
} else {
    _alert_back("非法操作");
}
//调用分页模块函数
global $offset, $pageSize, $_id;
$_id = "id=".$_html['id']."&";
_page("select tg_id from tg_photo where tg_sid='{$_GET['id']}'", 8);
//分页数据
$result2 = _query("select 
                        tg_id, 
                        tg_username, 
                        tg_name,
                        tg_url,
                        tg_readcount
                     from 
                        tg_photo
                    where
                        tg_sid='{$_GET['id']}'
                order by tg_date desc limit {$offset},{$pageSize}");

?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--图片展示</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/photo_show.css">
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="photo_show">
        <h2><?php echo $_html['name'] ?></h2>
        <?php
        if (($_html['type'] == 0) || (@$_COOKIE['photo'.$_html['id']] == $_html['name']) || (isset($_SESSION['admin']))) {
        while (!!($rows = $result2->fetch_assoc())) {
        ?>
        <dl>
            <dt><a href="photo_detail.php?id=<?php echo $rows['tg_id'] ?>"><img src="thumb.php?filename=<?php echo $rows['tg_url'] ?>&percent=0.3" /></a></dt>
            <dd><a href="photo_detail.php?id=<?php echo $rows['tg_id'] ?>"><?php echo $rows['tg_name'] ?></a></dd>
            <dd>浏览量(<strong><?php echo $rows['tg_readcount'] ?></strong>)</dd>
            <dd>上传者:<?php echo $rows['tg_username'] ?></dd>
            <?php
            if (@$rows['tg_username'] == @$_COOKIE['username'] || isset($_SESSION['admin'])) {
            ?>
            <dd>[<a href="photo_show.php?action=delete&id=<?php echo $rows['tg_id'] ?>">删除</a>]</dd>
            <?php } ?>
        </dl>
        <?php
        }
        $id = $_GET['id'];
        //调用分页函数(1表示数字分页, 2表示文本分页)
        _paging(1, "个会员");
        ?>
        <p><a href="photo_add_img.php?id=<?php echo $_html['id'] ?>">上传图片</a></p>
        <?php
        } else {
            echo '<form method="post" action="photo_show.php?id='.$_html['id'].'">
                      <label for="password">请输入密码:</label>
                      <input type="password" name="password" />
                      <input type="submit" value="提交" />
                  </form>';
        }
        ?>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>