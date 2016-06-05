<?php
session_start();
//定义需要调用某些文件所需的常量
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//读取XML文件
$xml_parsed_array = _get_xml("new.xml");
//读取帖子列表
//调用分页模块函数
global $offset, $pageSize;
_page("select tg_id from tg_article where tg_reid=0", 10);
//分页数据
$result2 = _query("select tg_id, tg_title, tg_type, tg_readcount, tg_commentcount 
                          from tg_article 
                          where tg_reid=0
                          order by tg_date 
                          desc limit {$offset},{$pageSize}")
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>多用户留言系统</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/index.css">
    <link rel="shortcut icon" href="images/favicon.ico">
    <script src="./script/blog.js"></script>
</head>
<body>
<?php
require_once (ROOT.'include/header.inc.php');
?>
    <div id="user">
        <h2>新进会员</h2>
        <dl>
            <dd class="user"><?php echo $xml_parsed_array['username'] ?>(<?php echo $xml_parsed_array['sex'] ?>)</dd>
            <dt><img src="<?php echo $xml_parsed_array['face'] ?>" alt="头像" /></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $xml_parsed_array['id'] ?>">发消息</a></dd>
            <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $xml_parsed_array['id'] ?>">&nbsp;&nbsp;加为好友</a></dd>
            <dd class="guest"><a href="javascript:;" name="guest" title="<?php echo $xml_parsed_array['id'] ?>">写留言</a></dd>
            <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $xml_parsed_array['id'] ?>">送花</a></dd>
            <dd class="email">邮件: <a href="mailto:<?php echo $xml_parsed_array['email'] ?>"><?php echo $xml_parsed_array['email'] ?></a></dd>
            <dd class="url">网址: <a href="<?php echo $xml_parsed_array['url'] ?>" target="_blank"><?php echo $xml_parsed_array['url'] ?></a></dd>
        </dl>
    </div>

    <div id="list">
        <h2>帖子列表</h2>
        <a href="post.php" class="post"></a>
        <ul class="article">
            <?php while (!!($rows = $result2->fetch_assoc())) { ?>
                <li>
                    <em>
                        阅读数(<strong><?php echo $rows['tg_readcount'] ?></strong>)
                        评论数(<strong><?php echo $rows['tg_commentcount'] ?></strong>)
                    </em>
                    <img src="images/icon<?php echo $rows['tg_type'] ?>.png" />
                    <a href="article.php?id=<?php echo $rows['tg_id'] ?>"><?php echo _title($rows['tg_title'], 20) ?>
                    </a>
                </li>
            <?php
                } 
             ?>
        </ul>
        <?php 
            //调用分页函数(1表示数字分页, 2表示文本分页)
                _paging(2, "条帖子");
        ?>
    </div>
    <div id="pics">
        <h2>最新图片</h2>
    </div>
<?php
require_once (ROOT.'include/footer.inc.php');
?>
</body>
</html>