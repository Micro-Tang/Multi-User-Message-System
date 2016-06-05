<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/25 下午1:59
 */
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//防止游客进入本页面
if (!isset($_COOKIE['username'])) {
    _alert_back('游客不能进入本页面');
}

if (@$_GET['action'] == 'delete') {
    $conn->query("delete from tg_message where tg_id={$_GET['tg_id']}");
    //跳转页面
    if (mysqli_affected_rows($conn) == 1) {
        //关闭数据库
        $conn->close();
        _location("删除成功", "member_message.php");
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("删除失败");
    }
    exit();
}

if (@$_GET['id']) {
    $result = $conn->query("select tg_id, tg_state, tg_fromuser, tg_content, tg_date
                                    from tg_message
                                    where tg_id='{$_GET['id']}'
                                    limit 1");
    $rows = $result->fetch_assoc();
    if (empty($rows['tg_state'])) {
        $conn->query("update tg_message set tg_state=1 where tg_id='{$_GET['id']}'");
    }
}
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--短信详情</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/member_message_detail.css">
        <script src="./script/member_message_detail.js"></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="member">
        <?php require_once (ROOT.'include/member.inc.php')?>
        <div id="member_main">
            <h2>信息详情</h2>
            <dl>
                <dd>
                    发&ensp;信&ensp;人: <?php echo $rows['tg_fromuser'] ?>
                </dd>
                <dd>
                    发信内容: <?php echo $rows['tg_content'] ?>
                </dd>
                <dd>
                    发信时间: <?php echo $rows['tg_date'] ?>
                </dd>
                <dd>
                    <input type="button" id="cancle" value="返回列表" />
                    <input type="button" id="delete" name="<?php echo $rows['tg_id'] ?>" value="删除短信" />
                </dd>
            </dl>
        </div>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php')
    ?>
    </body>
    </html>
