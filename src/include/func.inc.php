<?php
/**
 * Author: tangkun
 * Date_Time: 16/5/12 上午11:21
 */
/**
 * _time() {计算当前时间}
 * @access public
 * @return mixed
 */
function _time() {
    $_mtime = explode(' ', microtime());
    return ($_mtime[1] + $_mtime[0]);
}

/**
 * _get_xml($xml) 获取并解析XML文件内容
 * @param $xml
 * @access public
 * @return array
 */
function _get_xml($xml) {
    $xml_parsed_array = array();
    $xmlfile = $xml;
    if (file_exists($xmlfile)) {
        $xml_string = file_get_contents($xmlfile);
        preg_match_all('#<vip>(.*)</vip>#s', $xml_string, $dom);
        foreach($dom[1] as $value) {
            preg_match_all('#<id>(.*)</id>#', $value, $id);
            preg_match_all('#<username>(.*)</username>#', $value, $username);
            preg_match_all('#<sex>(.*)</sex>#', $value, $sex);
            preg_match_all('#<face>(.*)</face>#', $value, $face);
            preg_match_all('#<email>(.*)</email>#', $value, $email);
            preg_match_all('#<url>(.*)</url>#', $value, $url);
            $xml_parsed_array['id'] = $id[1][0];
            $xml_parsed_array['username'] = $username[1][0];
            $xml_parsed_array['sex'] = $sex[1][0];
            $xml_parsed_array['face'] = $face[1][0];
            $xml_parsed_array['email'] = $email[1][0];
            $xml_parsed_array['url'] = $url[1][0];

        }
    } else {
        exit($xmlfile.'文件不存在');
    }
    return $xml_parsed_array;
}

/**
 * _set_xml($xmlfile, $clean)生成XML文件
 * @access public
 * @param $xmlfile
 * @param $clean
 * @return void
 */
function _set_xml($xmlfile, $clean) {
    $fp = fopen($xmlfile, "w");
    if (!$fp) {
        echo "出错";
        exit();
    }

    flock($fp,LOCK_EX);

    $string = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "<vip>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "\t<id>".$clean['id']."</id>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "\t<username>".$clean['username']."</username>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "\t<sex>".$clean['sex']."</sex>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "\t<face>".$clean['face']."</face>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "\t<email>".$clean['email']."</email>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "\t<url>".$clean['url']."</url>\r\n";
    fwrite($fp, $string, strlen($string));

    $string = "</vip>";
    fwrite($fp, $string, strlen($string));
    flock($fp,LOCK_UN);
    fclose($fp);
}

/**
 * _check_post_title($string, $min, $max) {检查表单标题长度}
 *
 * @access public
 * @param $string
 * @param $min
 * @param $max
 *
 * @return mixed
 */
function _check_post_title($string, $min, $max) {
     if (mb_strlen($string, 'utf-8') < $min || mb_strlen($string, 'utf-8') > $max) {
         _alert_back("标题长度不得少于".$min."位或者多于".$max."位");
     }
    return $string;
}

/**
 * [_ubb 解析发帖内容]
 * @param  [string] $string [description]
 * @return mixed
 */
function _ubb($string) {
    $string = nl2br($string);
    $string = preg_replace('/\[b\](.*)\[\/b\]/U', '<strong>\1</strong>', $string);
    $string = preg_replace('/\[i\](.*)\[\/i\]/U', '<em>\1</em>', $string);
    $string = preg_replace('/\[u\](.*)\[\/u\]/U', '<span style="text-decoration: underline">\1</span>', $string);
    $string = preg_replace('/\[s\](.*)\[\/s\]/U', '<span style="text-decoration: line-through">\1</span>', $string);
    $string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U', '<span style="font-size: \1px">\2</span>', $string);
    $string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U', '<span style="color: \1">\2</span>', $string);
    $string = preg_replace('/\[url\](.*)\[\/url\]/U', '<a href="\1" target="_blank">\1</a>', $string);
    $string = preg_replace('/\[email\](.*)\[\/email\]/U', '<a href="mailto:\1">\1</a>', $string);
    $string = preg_replace('/\[img\](.*)\[\/img\]/U', '<img src="\1" alt="图片"/>', $string);
    $string = preg_replace('/\[flash\](.*)\[\/flash\]/U', '<embed style="width: 480px;height: 400px" src="\1" />', $string);
    return $string;
}

/**
 * _check_post_content($string, $min, $max) {检查表单内容长度}
 *
 * @access public
 * @param $string
 * @param $min
 *
 * @return mixed
 */
function _check_post_content($string, $min) {
    if (mb_strlen($string, 'utf-8') < $min) {
        _alert_back("内容长度不得少于".$min."位");
    }
    return $string;
}

/**
* _title($str)
 * @param $str
 * @param   $num [<description>]
* @access public
* @return string
*
*/
function _title($str, $num) {
    if(mb_strlen($str, 'utf-8') > $num) {
        $str = mb_substr($str, 0, $num, 'utf-8')."...";
    }
    return $str;
}

/**
 * _alert_back($str){弹出消息并返回之前页面}
 * @param $str
 * @access public
 * @return void
 */
function _alert_back($str) {
    echo "<script>alert('$str')</script>";
    echo "<script>history.back()</script>";
    exit();
}

/**
 * _alert_close($str){弹出消息并关闭页面}
 * @param $str
 * @access public
 * @return void
 */
function _alert_close($str) {
    echo "<script>alert('$str')</script>";
    echo "<script>window.close()</script>";
    exit();
}

/**
 * _execute_time(){计算程序执行耗时}
 * @access public
 * @param $start {程序开始时间}
 * @param $end {程序结束时间}
 * @return double
 */
function _execute_time($start, $end) {
    return (sprintf('%.6f', ($end - $start)));
}

/**
 * _page($sql, $size) {分页模块函数}
 * @access public
 * @param $sql {查询数据库所有条数}
 * @param $size {每页显示条数}
 * @return void
 */
function _page($sql, $size) {
    global $page, $pageCount, $result_rows, $offset, $pageSize;
    //提取全部数据
    $result = _query($sql);
    $result_rows = $result->num_rows;
    //每页显示条数
    $pageSize = $size;
    if ($result_rows == 0) {
        $pageCount = 1;
    } else {
        //总页数
        $pageCount = ceil($result_rows / $pageSize);
    }
    //当前页数
    if (isset($_GET['page'])) {
        if(!empty($_GET['page'])) {
            if(intval($_GET['page']) > 0) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
        } else {
            $page = 1;
        }
    } else {
        $page = 1;
    }
    if ($page > $pageCount) {
        $page = $pageCount;
    }
    //当前数据指针
    $offset = ($page - 1) * $pageSize;
    $result->free_result();
}

/**
 * _paging($type) 分页函数
 * @access public
 * @param $type {1 or 2选择分页显示类型}
 * @param $string
 * @return void
 */
function _paging($type, $string) {
    global $page, $pageCount, $result_rows, $_id;
    if ($type == 1) {
        echo '<div id="page_num">';
        echo '<ul>';
        for ($i = 1; $i <= $pageCount; $i++) {
            if($page == $i) {
                echo "<li><a href='".$_SERVER['SCRIPT_NAME']."?".$_id."page={$i}' class='selected'>{$i}</a></li>";
            } else {
                echo "<li><a href='".$_SERVER['SCRIPT_NAME']."?".$_id."page={$i}'>{$i}</a></li>";
            }
        }
        echo '</ul>';
        echo '</div>';
    } else if($type == 2) {
        echo '<div id="page_text">';
        echo '<ul>';
        echo '<li>'.$page.'/'.$pageCount.'页 | </li>';
        echo "<li>共有 <strong>$result_rows</strong> $string | </li>";
        if ($page > 1) {
            echo "<li><a href='".$_SERVER['SCRIPT_NAME']."?".$_id."page=1'>首页</a> | </li>";
            echo "<li><a href='".$_SERVER['SCRIPT_NAME']."?".$_id."page=".($page-1)."'>上一页</a> | </li>";
        }
        if ($page < $pageCount) {
            echo "<li><a href='".$_SERVER['SCRIPT_NAME']."?".$_id."page=".($page+1)."'>下一页</a> | </li>";
            echo "<li><a href='".$_SERVER['SCRIPT_NAME']."?".$_id."page=".$pageCount."'>尾页</a> |</li>";
        }
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * _location 弹出信息并跳转到制定页面
 * @access public
 * @param $info {弹框信息}
 * @param $url {跳转页面地址}
 */
function _location($info, $url) {
    echo "<script>alert('$info');location.href='$url'</script>";
    exit();
}

/**
 * _login_state()登录状态检测
 * @access public
 * @return void
 */
function _login_state() {
    if (isset($_COOKIE['username'])) {
        echo "<script>alert('登录状态无法进行本操作!')</script>";
        echo "<script>history.back()</script>";
        exit();
    }
}

/**
 * _html($str) {返回转义后的字符串}
 * @access public
 * @param $str
 * @return string
 */
function _html($str) {
    return htmlspecialchars($str);
}

/**
 * _setCookies{设置cookie}
 * @access public
 * @param $username
 * @param $time
 * @return void
 */
function _setCookies($username, $time) {
    switch ($time) {
        case '0':
            setcookie('username', $username);
            break;
        case '1':
            setcookie('username', $username, time() + 86400);
            break;
        case '2':
            setcookie('username', $username, time() + 604800);
            break;
        case '3':
            setcookie('username', $username, time() + 2592000);
            break;
    }
}

/**
 * _unsetCookies退出登录
 * @access public
 * @return void
 */
function _unsetCookies() {
    setcookie('username','',time()-1);
    setcookie('uniqid','',time()-2);
    session_destroy();
    header("Location:index.php");
}

/**
 * _thumb 生成图片缩略图
 * @param $filename
 * @param $percent
 */
function _thumb($filename, $percent) {
    //获取文件后缀
    $_n = explode('.', $filename);
    //获取文件信息,长和高
    list($_width, $height) = getimagesize($filename);
    //生成微缩的长和高
    $_new_width = $_width * $percent;
    $_new_height = $height * $percent;
    //创建微缩画布
    $_image_p = imagecreatetruecolor($_new_width, $_new_height);
    //按照已有图片创建画布
    switch($_n[1]) {
        case 'jpg':
            $_image = imagecreatefromjpeg($filename);
            break;
        case 'gif':
            $_image = imagecreatefromjpeg($filename);
            break;
        case 'png':
            $_image = imagecreatefromjpeg($filename);
            break;
    }
    //将原图采集后重新复制到新图上就微缩了
    imagecopyresampled($_image_p, $_image, 0, 0, 0, 0, $_new_width, $_new_height, $_width, $height);
    //清除缓冲区
    ob_clean();
    //生成png标头文件
    header('Content-Type: image/png');
    //生成png
    imagepng($_image_p);

    //销毁
    imagedestroy($_image);
    imagedestroy($_image_p);
}
/**
 * _code()随机产生验证码图片
 * @access public
 * @param $_vari_num {验证码位数}
 * @param $flag=false {是否显示边框标志}
 * @return void
 */
function _code($_vari_num, $flag=false) {
    //验证码字符
    $vari_code = "";
    //随机字符急
    $chars = "522725199508281211tangkunTANGKUN";
    for ($i = 0; $i < $_vari_num; $i++) {
        $vari_code .= $chars[mt_rand(0, strlen($chars))];
    }
    $_SESSION['varify'] = $vari_code;
    //根据验证码个数确定画布宽度和高度
    $width = 100 + ($_vari_num - 4) * 20;
    $height = 35;
    //创建画布
    $img = imagecreatetruecolor($width, $height);
    //创建背景颜色
    $bgcolor = imagecolorallocate($img, 230, 230, 230);
    //填充颜色
    imagefill($img, 0, 0, $bgcolor);

    if ($flag) {
        //边框颜色
        $rectColor = imagecolorallocate($img, 100, 100, 100);
        //画边框
        imagerectangle($img,0, 0,$width - 1, $height - 1, $rectColor);
    }

    //随机生成干扰线条
    for ($i = 0; $i < 6; $i++) {
        //干扰线颜色
        $lineColor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        //填充干扰线
        imageline($img, rand(1, $width), rand(1, $height), rand(1, $width), rand(1, $height),$lineColor);
    }

    //随机生成干扰点
    for ($i = 0; $i < 200; $i++) {
        //干扰点颜色
        $pointColor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        //填充干扰点
        //imagestring($img, 1, mt_rand(1, $width), mt_rand(1, $height), '.', $pointColor);
        imagesetpixel($img, mt_rand(1, $width), mt_rand(1, $height), $pointColor);
    }

    //填充验证码
    for ($i = 0; $i < $_vari_num; $i++) {
        $x = ($i * $width / $_vari_num) + mt_rand(1, 5);
        $y = rand(5, $height / 2);
        //字体大小
        $fontSize = 6;
        //字体颜色
        $fontColor = imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200));
        imagestring($img, $fontSize, $x, $y, $_SESSION['varify'][$i], $fontColor);
    }

    //清除缓冲区
    ob_clean();

    //输出图像
    header('Content-Type: image/png');
    imagepng($img);
    imagedestroy($img);
}
