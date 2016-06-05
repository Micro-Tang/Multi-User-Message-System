<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/25 下午12:30
 */
//定义常量,以调用所需文件
define('TK', true);
//引入公共文件
require_once (dirname(__FILE__).'/include/common.inc.php');
//防止游客进入本页面
if (!isset($_COOKIE['username'])) {
    _alert_back('游客不能进入本页面');
}

//验证好友
if (@$_GET['action'] == 'check') {
    $conn->query("update tg_friend set tg_state=1 where tg_id='{$_GET['id']}'");
    if ($conn->affected_rows == 1) {
        //关闭数据库
        $conn->close();
        _location("验证成功,你们已经成为好友!", "member_friend.php");
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("验证失败");
    }
}

//批删除
if(@$_GET['action'] == 'delete' && @$_POST['ids']) {
    $arr = array();
    $arr = implode(',', $_POST['ids']);
    $conn->query("delete from tg_friend
                              WHERE 
                              tg_id IN 
                              ({$arr})");
    if ($conn->affected_rows > 0) {
        //关闭数据库
        $conn->close();
        _location("删除成功", "member_friend.php");
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("删除失败");
    }
}

//调用分页模块函数
global $offset, $pageSize;
_page("select tg_id from tg_friend where tg_touser='{$_COOKIE['username']}' or tg_fromuser='{$_COOKIE['username']}'", 10);
//分页数据
$result2 = _query("select tg_id, tg_state,tg_touser, tg_fromuser, tg_content, DATE(tg_date) as tg_date
                        from tg_friend
                        where tg_touser='{$_COOKIE['username']}' or tg_fromuser='{$_COOKIE['username']}'
                        order by tg_date desc limit {$offset},{$pageSize}");
?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>多用户留言系统--好友列表</title>
        <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
        <link rel="stylesheet" type="text/css" href="./styles/1/member_friend.css">
        <script src="script/member_friend.js" ></script>
    </head>
    <body>
    <?php require_once (ROOT.'include/header.inc.php')?>
    <div id="member">
        <?php require_once (ROOT.'include/member.inc.php')?>
        <div id="member_main">
            <h2>好友管理中心</h2>
            <form method="post" action="?action=delete">
                <table>
                    <tr>
                        <th>好友</th>
                        <th>请求内容</th>
                        <th>时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    <?php
                    while (!!($rows = $result2->fetch_assoc())) {
                        if ($rows['tg_touser'] == $_COOKIE['username']) {
                            $friend = $rows['tg_fromuser'];
                            if (empty($rows['tg_state'])) {
                                $state = '<a href="?action=check&id='.$rows['tg_id'].'" style="color: red">你未验证</a>';
                            } else {
                                $state = '<span style="color: green">通过</span>';
                            }
                        } elseif ($rows['tg_fromuser'] == $_COOKIE['username']) {
                            $friend = $rows['tg_touser'];
                            if (empty($rows['tg_state'])) {
                                $state = '<span style="color: blue">对方未验证</span>';
                            } else {
                                $state = '<span style="color: green">通过</span>';
                            }
                        }
                        ?>
                        <tr>
                            <td><?php echo $friend ?></td>
                            <td title="<?php echo $rows['tg_content'] ?>"><?php echo _title($rows['tg_content'], 8) ?></td>
                            <td><?php echo $rows['tg_date'] ?></td>
                            <td><?php echo $state; ?></td>
                            <td><input name="ids[]" value="<?php echo $rows['tg_id'] ?>" type="checkbox" /></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5"><label for="all">全选</label><input type="checkbox" name="chkall" id="all" /></td>
                    </tr>
                    <tr>
                        <td colspan="5"><input type="submit" value="删除"/></td>
                    </tr>
            </form>
            </table>
            <?php
            //调用分页函数(1表示数字分页, 2表示文本分页)
            _paging(2, "数据");
            ?>
        </div>
    </div>
    <?php
    require_once (ROOT.'include/footer.inc.php')
    ?>
    </body>
    </html>
