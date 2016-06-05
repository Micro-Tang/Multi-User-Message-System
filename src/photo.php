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
//调用分页模块函数
global $offset, $pageSize;
_page("select tg_id from tg_dir", 20);
//分页数据
$result2 = _query("select tg_name, tg_type,tg_id from tg_dir order by tg_date desc limit {$offset},{$pageSize}");
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--相册</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/photo.css">
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="photo">
        <h2>相册列表</h2>
        <?php
        while (!!($rows = $result2->fetch_assoc())) {
            if (empty($rows['tg_type'])) {
                $type = '(公开)';
            } else {
                $type = '(私密)';
            }
        ?>
        <dl>
            <a href="photo_show.php?id=<?php echo $rows['tg_id'] ?>"><dt></dt></a>
            <dd><a href="photo_show.php?id=<?php echo $rows['tg_id'] ?>"><?php echo $rows['tg_name'] ?>&nbsp;<?php echo $type ?></a></dd>
            <?php if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) { ?>
            <dd>[<a href="photo_modify_dir.php?id=<?php echo $rows['tg_id'] ?>">修改</a>]&emsp;[删除]</dd>
            <?php } ?>
        </dl>
        <?php
        }
        //调用分页函数(1表示数字分页, 2表示文本分页)
        _paging(2, "个相册");
        ?>
        <?php if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) { ?>
        <p><a href="photo_add_dir.php">添加相册</a></p>
        <?php } ?>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>