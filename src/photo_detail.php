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
    if (!!$rows = _query("select tg_sid, tg_id, tg_content, tg_url, tg_username, tg_name, tg_readcount, tg_date from tg_photo where tg_id='{$_GET['id']}'")->fetch_assoc()) {
        //防止穿插访问私密相册图片
        if (!isset($_SESSION['admin'])) {
            if (!!$_dirs = _query("select tg_id, tg_name, tg_type from tg_dir where tg_id='{$rows['tg_sid']}'")->fetch_assoc()) {
                if (!empty($_dirs['tg_type'] && $_COOKIE['photo' . $_dirs['tg_id']] != $_dirs['tg_name'])) {
                    _alert_back('非法操作');
                }
            }
        }
        //增加阅读量
        _query("update tg_photo set tg_readcount=tg_readcount+1 where tg_id='{$_GET['id']}'");
        $_html = array();
        $_html['sid'] = $rows['tg_sid'];
        $_html['id'] = $rows['tg_id'];
        $_html['name'] = $rows['tg_name'];
        $_html['username'] = $rows['tg_username'];
        $_html['url'] = $rows['tg_url'];
        $_html['readcount'] = $rows['tg_readcount'];
        $_html['date'] = $rows['tg_date'];
        $_html['content'] = $rows['tg_content'];

        //获取当前图片的前一张的ID(上一张)
        $_html['preid'] = _query("select min(tg_id) 
                                          as 
                                            id 
                                        from 
                                            tg_photo
                                       where
                                            tg_sid='{$_html['sid']}'
                                         AND
                                            tg_id > '{$_html['id']}'
                                  ")->fetch_assoc();
        if (!empty($_html['preid']['id'])) {
            $_pre = '<a href="photo_detail.php?id='.$_html['preid']['id'].'">上一页</a>';
        } else {
            $_pre = "<a href='javascript: alert(\"没有了\");'>上一页</a>";
        }

        //获取当前图片的后一张的ID(下一张)
        $_html['nextid'] = _query("select max(tg_id) 
                                          as 
                                            id 
                                        from 
                                            tg_photo
                                       where
                                            tg_sid='{$_html['sid']}'
                                         AND
                                            tg_id < '{$_html['id']}'
                                  ")->fetch_assoc();
        if (!empty($_html['nextid']['id'])) {
            $_next = '<a href="photo_detail.php?id='.$_html['nextid']['id'].'">下一页</a>';
        } else {
            $_next = "<a href='javascript: alert(\"没有了\");'>下一页</a>";
        }
    } else {
        _alert_back("不存在此图片");
    }
} else {
    _alert_back("非法操作");
}

?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--图片详情</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/photo_detail.css">
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="photo_detail">
        <h2>图片详情</h2>
        <dl>
            <dd class="name"><?php echo $_html['name'] ?></dd>
            <dt><?php echo @$_pre ?><img src="thumb.php?filename=<?php echo $_html['url'] ?>&percent=0.7" /><?php echo @$_next ?></dt>
            <dd>浏览量(<strong><?php echo $_html['readcount'] ?></strong>)</dd>
            <dd>上传者:<?php echo $_html['username'] ?>&nbsp;发表于:<?php echo $_html['date'] ?></dd>
            <?php if (!empty($_html['content'])) { ?>
            <dd>简介: <?php echo $_html['content'] ?></dd>
            <?php } ?>
            <dd>[<a href="photo_show.php?id=<?php echo $_html['sid'] ?>">返回相册列表</a>]</dd>
        </dl>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>