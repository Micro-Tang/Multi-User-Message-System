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
//批删除
if(@$_GET['action'] == 'delete' && @$_POST['ids']) {
    $arr = array();
    $arr = implode(',', $_POST['ids']);
    $conn->query("delete from tg_flower
                              WHERE 
                              tg_id IN 
                              ({$arr})");
    if ($conn->affected_rows > 0) {
        //关闭数据库
        $conn->close();
        _location("删除成功", "member_flower.php");
    } else {
        //关闭数据库
        $conn->close();
        _alert_back("删除失败");
    }
}

//调用分页模块函数
global $offset, $pageSize;
_page("select tg_id from tg_flower where tg_touser='{$_COOKIE['username']}'", 10);
//分页数据
$result2 = _query("select tg_id, tg_fromuser, tg_flower, tg_content, tg_date
                        from tg_flower
                        where tg_touser='{$_COOKIE['username']}'
                        order by tg_date desc limit {$offset},{$pageSize}")

?>
<DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>多用户留言系统--花朵管理</title>
    <link rel="stylesheet" type="text/css" href="./styles/1/basic.css">
    <link rel="stylesheet" type="text/css" href="./styles/1/member_flower.css">
    <script src="script/member_flower.js" ></script>
</head>
<body>
<?php require_once (ROOT.'include/header.inc.php')?>
<div id="member">
    <?php require_once (ROOT.'include/member.inc.php')?>
    <div id="member_main">
        <h2>花朵管理中心</h2>
        <form method="post" action="?action=delete">
        <table>
            <tr>
                <th>送花使者</th>
                <th>送花留言</th>
                <th>时间</th>
                <th>花朵数量</th>
                <th>操作</th>
            </tr>
            <?php
                $flower_count = 0;
                while (!!($rows = $result2->fetch_assoc())) {
                    $flower_count += $rows['tg_flower'];
            ?>
            <tr>
                <td><?php echo $rows['tg_fromuser'] ?></td>
                <td title="<?php echo $rows['tg_content'] ?>"><?php echo _title($rows['tg_content'], 8) ?></td>
                <td><?php echo $rows['tg_date'] ?></td>
                <td><?php echo "x".$rows['tg_flower']."朵 "; ?><img src="images/flower.png" /></td>
                <td><input name="ids[]" value="<?php echo $rows['tg_id'] ?>" type="checkbox" /></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="5">共收到<span style="color: #ce8483;font-weight: bolder"><?php echo $flower_count ?></span>朵<img src="images/flower.png" /></td>
            </tr>
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
        _paging(1, "数据");
        ?>
    </div>
</div>
<?php
require_once (ROOT.'include/footer.inc.php')
?>
</body>
</html>
