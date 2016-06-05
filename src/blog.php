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
_page("select tg_id from tg_user", 20);
//分页数据
$result2 = _query("select tg_id, tg_username, tg_face, tg_sex from tg_user order by tg_reg_time desc limit {$offset},{$pageSize}")
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--博友</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/blog.css">
        <script src="./script/blog.js"></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="blog">
        <h2>博友列表</h2>
        <?php while (!!($rows = $result2->fetch_assoc())) { ?>
        <dl>
            <dd class="user"><?php echo $rows['tg_username']; ?>(<?php echo $rows['tg_sex'] ?>)</dd>
            <dt><img src="<?php echo $rows['tg_face']; ?>" alt="头像" /></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $rows['tg_id'] ?>">发消息</a></dd>
            <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $rows['tg_id'] ?>">&nbsp;&nbsp;加为好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $rows['tg_id'] ?>">送花</a></dd>
        </dl>
        <?php
        }
        //调用分页函数(1表示数字分页, 2表示文本分页)
        _paging(2, "个会员");
        ?>
    </div>
    <?php
    $result2->free_result();
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>