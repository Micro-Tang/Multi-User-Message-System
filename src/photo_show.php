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

if (isset($_GET['id'])) {
    if (!!$rows = _query("select tg_id, tg_name from tg_dir where tg_id='{$_GET['id']}'")->fetch_assoc()) {
        $_html = array();
        $_html['id'] = $rows['tg_id'];
        $_html['name'] = $rows['tg_name'];
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
        <?php while (!!($rows = $result2->fetch_assoc())) { ?>
        <dl>
            <dt><a href="photo_detail.php?id=<?php echo $rows['tg_id'] ?>"><img src="thumb.php?filename=<?php echo $rows['tg_url'] ?>&percent=0.3" /></a></dt>
            <dd><a href="photo_detail.php?id=<?php echo $rows['tg_id'] ?>"><?php echo $rows['tg_name'] ?></a></dd>
            <dd>浏览量(<strong><?php echo $rows['tg_readcount'] ?></strong>)</dd>
            <dd>上传者:<?php echo $rows['tg_username'] ?></dd>
        </dl>
        <?php
        }
        $id = $_GET['id'];
        //调用分页函数(1表示数字分页, 2表示文本分页)
        _paging(1, "个会员");
        ?>
        <p><a href="photo_add_img.php?id=<?php echo $_html['id'] ?>">上传图片</a></p>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>