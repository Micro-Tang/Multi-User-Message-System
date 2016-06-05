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
//处理回帖数据
if (@$_GET['action'] === 'rearticle') {
    if(strtolower(@$_POST['varify']) != strtolower($_SESSION['varify']))
    {
        _alert_back('验证码不正确');
    }
    $rearticle_arr = array();
    $rearticle_arr['reid'] = mysqli_real_escape_string($conn, $_POST['reid']);
    $rearticle_arr['username'] = $_COOKIE['username'];
    $rearticle_arr['type'] = mysqli_real_escape_string($conn, $_POST['type']);
    $rearticle_arr['title'] = mysqli_real_escape_string($conn, $_POST['title']);
    $rearticle_arr['content'] = mysqli_real_escape_string($conn, $_POST['content']);

    //写入数据库
    $conn->query("insert into tg_article(
                                        tg_reid,
                                        tg_username, 
                                        tg_type,
                                        tg_title,
                                        tg_content,
                                        tg_date)
                               values (
                                        '{$rearticle_arr['reid']}',
                                        '{$rearticle_arr['username']}',
                                        '{$rearticle_arr['type']}',
                                        '{$rearticle_arr['title']}',
                                        '{$rearticle_arr['content']}',
                                        now()
                               )");

    //跳转页面
    if (mysqli_affected_rows($conn) == 1) {
        _query("update tg_article set tg_commentcount=tg_commentcount+1 where tg_reid=0 and tg_id='{$rearticle_arr['reid']}'");
        //注册成功
        //关闭数据库
        $conn->close();
        _location("回帖成功", "article.php?id=".$rearticle_arr['reid'] );
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("回帖失败");
    }
}
//读取数据
if (@$_GET['id']) {
    $res = _query("select * from tg_article where tg_reid=0 and tg_id='{$_GET['id']}'");
    $row = $res->fetch_assoc();
    //增加阅读量
    _query("update tg_article set tg_readcount=tg_readcount+1 where tg_id='{$_GET['id']}'");
    if ($row) {
        $article_arr = array();
        $article_arr['reid'] = $row['tg_id'];
        $article_arr['username'] = $row['tg_username'];
        $article_arr['type'] = $row['tg_type'];
        $article_arr['title'] = $row['tg_title'];
        $article_arr['content'] = $row['tg_content'];
        $article_arr['readcount'] = $row['tg_readcount'];
        $article_arr['commentcount'] = $row['tg_commentcount'];
        $article_arr['date'] = $row['tg_date'];

        //拿出用户名,查找用户信息
        $res = _query("select * from tg_user where tg_username='{$article_arr['username']}'");
        $row = $res->fetch_assoc();
        if ($row) {
            $user_arr = array();
            $user_arr['id'] = $row['tg_id'];
            $user_arr['sex'] = $row['tg_sex'];
            $user_arr['face'] = $row['tg_face'];
            $user_arr['email'] = $row['tg_email'];
            $user_arr['url'] = $row['tg_url'];
            $user_arr['switch'] = $row['tg_switch'];
            $user_arr['autograph'] = $row['tg_autograph'];

            //带参分页
            global $id, $page;
            $id = 'id='.$article_arr['reid'].'&';

            //修改帖子
            if (@$_COOKIE['username'] === $article_arr['username']) {
                $subject_modify = '[<a href="article_modify.php?id='.$article_arr['reid'].'">修改</a>]';
            }

            //读取回帖
            //调用分页模块函数
            global $offset, $pageSize;
            _page("select tg_id from tg_article where tg_reid='{$article_arr['reid']}'", 10);
            //分页数据
            $result2 = _query("select * from tg_article where tg_reid='{$article_arr['reid']}' order by tg_date asc limit {$offset},{$pageSize}");
        } else {
            //用户已被删除
        }
    } else {
        _alert_back("不存在此条文章记录");
    }
} else {
    _alert_back("非法操作");
}
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--帖子详情</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/article.css">
        <script src="./script/article.js"></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="article">
        <h2>帖子详情</h2>
        <?php if ($page == 1) { ?>
        <div id="subject">
            <dl>
                <dd class="user"><?php echo $article_arr['username'] ?>(<?php echo $user_arr['sex'] ?>)[楼主]</dd>
                <dt><img src="<?php echo $user_arr['face'] ?>" alt="头像" /></dt>
                <dd class="message"><a href="javascript:;" name="message" title="<?php echo $user_arr['id'] ?>">发消息</a></dd>
                <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $user_arr['id'] ?>">&nbsp;&nbsp;加为好友</a></dd>
                <dd class="guest"><a href="javascript:;" name="guest" title="<?php echo $user_arr['id'] ?>">写留言</a></dd>
                <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $user_arr['id'] ?>">送花</a></dd>
                <dd class="email">邮件: <a href="mailto:<?php echo $user_arr['email'] ?>"><?php echo $user_arr['email'] ?></a></dd>
                <dd class="url">网址: <a href="<?php echo $user_arr['url'] ?>" target="_blank"><?php echo $user_arr['url'] ?></a></dd>
            </dl>
        </div>
        <div class="content">
            <div class="user">
                <span><?php echo @$subject_modify; ?> 1#</span><?php echo $article_arr['username'] ?> | 发表于: <?php echo $article_arr['date'] ?>
            </div>
            <h3>主题: <?php echo $article_arr['title'] ?><img src="images/icon<?php echo $article_arr['type'] ?>.png" alt="icon<?php echo $article_arr['type'] ?>" /></h3>
            <div class="detail">
                <?php echo _ubb($article_arr['content']) ?>
                <p class="autograph"><?php echo (($user_arr['switch'] == 1) ? $user_arr['autograph']."  ------------ 个性签名" : "" )?></p>
            </div>
            <div class="read">
                阅读(<?php echo $article_arr['readcount'] ?>)&emsp;&emsp;评论(<?php echo $article_arr['commentcount'] ?>)
            </div>
        </div>
        <?php } ?>

        <p class="line"></p>
        <?php
        $i = 2;
            $html = array();
            while(!!$row = $result2->fetch_assoc()) {
                $html['username'] = $row['tg_username'];
                $html['title'] = $row['tg_title'];
                $html['type'] = $row['tg_type'];
                $html['content'] = $row['tg_content'];
                $html['date'] = $row['tg_date'];

        //拿出用户名,查找用户信息
        $res = _query("select * from tg_user where tg_username='{$html['username']}'");
        $re_row = $res->fetch_assoc();
        if ($row) {
            $html['id'] = $re_row['tg_id'];
            $html['sex'] = $re_row['tg_sex'];
            $html['face'] = $re_row['tg_face'];
            $html['email'] = $re_row['tg_email'];
            $html['url'] = $re_row['tg_url'];
            $html['switch'] = $re_row['tg_switch'];
            $html['autograph'] = $re_row['tg_autograph'];
        } else {
            //被删除
        }
        //跟帖回复
                if (@$_COOKIE['username']) {
                    $re = '<span>[<a href="#ree" name="re" title="' . ($i + ($page - 1) * $pageSize) . ' 楼的' . $html['username'] . '">回复</a>]</span>';
                }
        ?>
        <div class="re">
            <dl>
                <dd class="user"><?php echo $html['username'] ?>(<?php echo $html['sex'] ?>)</dd>
                <dt><img src="<?php echo $html['face'] ?>" alt="头像" /></dt>
                <dd class="message"><a href="javascript:;" name="message" title="<?php echo $html['id'] ?>">发消息</a></dd>
                <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $html['id'] ?>">&nbsp;&nbsp;加为好友</a></dd>
                <dd class="guest"><a href="javascript:;" name="guest" title="<?php echo $html['id'] ?>">写留言</a></dd>
                <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $html['id'] ?>">送花</a></dd>
                <dd class="email">邮件: <a href="mailto:<?php echo $html['email'] ?>"><?php echo $html['email'] ?></a></dd>
                <dd class="url">网址: <a href="<?php echo $html['url'] ?>" target="_blank"><?php echo $html['url'] ?></a></dd>
            </dl>
        </div>
        <div class="content">
            <div class="user">
                <span><?php echo $i + ($page - 1) * $pageSize ?>#</span><?php echo $html['username'] ?> | 回复于: <?php echo $html['date'] ?>
            </div>
            <h3>主题: <?php echo $html['title'] ?><img src="images/icon<?php echo $html['type'] ?>.png" alt="icon<?php echo $html['type'] ?>" />&emsp;<?php echo @$re ?></span></h3>
            <div class="detail">
                <?php echo _ubb($html['content']) ?>
                <p class="autograph"><?php echo (($html['switch'] == 1) ? $html['autograph']."  ------------ 个性签名" : "" )?></p>
            </div>
        </div>
        <p class="line"></p>
        <?php
             $i++;
            }
            _paging(1, '条回帖');
        ?>
        <p class="line"></p>
        <?php if (isset($_COOKIE['username'])) { ?>
        <a name="ree"></a>
        <form method="post" action="?action=rearticle">
            <input type="hidden" name="reid" value="<?php echo $article_arr['reid'] ?>" />
            <input type="hidden" name="type" value="<?php echo $article_arr['type'] ?>" />
            <dl>
                <dd>
                    标&emsp;&emsp;题: <input type="text" id="title" name="title" class="text" value="RE: <?php echo $article_arr['title'] ?>" />(*必填, 2-40位)
                </dd>
                <dd>
                    <?php require_once 'include/ubb.php'; ?>
                    <textarea name="content" rows="9"></textarea>
                </dd>
                <dd>
                    验&ensp;证&ensp;码: <input type="text" id="varify" name="varify" class="text yzm"/>
                    <img class="varify" src="./varify.php" onclick="this.src='./varify.php?tm='+Math.random()"/>
                    <input type="submit" class="button" name="submit" value="回复""/>
                </dd>
            </dl>
        </form>
        <?php } ?>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php');
    ?>
    </body>
    </html>