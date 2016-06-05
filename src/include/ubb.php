<?php
//防止恶意调用
if (!defined('TK')) {
    exit("非法调用");
}
?>
<div id="ubb">
    <img src="images/fontsize.png" title="字体大小" alt="字体大小" />
    <img src="images/space.png" title="线条" alt="线条" />
    <img src="images/bold.png" title="加粗" alt="加粗" />
    <img src="images/italic.png" title="斜体" alt="斜体" />
    <img src="images/underline.png" title="下划线" alt="下划线" />
    <img src="images/strikethrough.png" title="删除线" alt="删除线" />
    <img src="images/space.png" title="线条" alt="线条" />
    <img src="images/color.png" title="字体颜色" alt="字体颜色" />
    <img src="images/url.png" title="链接" alt="链接" />
    <img src="images/email.png" title="邮件" alt="邮件" />
    <img src="images/image.png" title="图片" alt="图片" />
    <img src="images/flash.png" title="gif" alt="gif" />
    <img src="images/movie.png" title="视频" alt="视频" />
    <img src="images/space.png" title="线条" alt="线条" />
    <img src="images/left.png" title="居左" alt="居左" />
    <img src="images/center.png" title="居中" alt="居中" />
    <img src="images/right.png" title="居右" alt="居右" />
    <img src="images/space.png" title="线条" alt="线条" />
    <img src="images/jia.png" title="加" alt="加" />
    <img src="images/jian.png" title="减" alt="减" />
    <img src="images/wen.png" title="疑问" alt="疑问" />
</div>
<div id="font">
    <strong onclick="font(10)">10px</strong>
    <strong onclick="font(12)">12px</strong>
    <strong onclick="font(14)">14px</strong>
    <strong onclick="font(16)">16px</strong>
    <strong onclick="font(18)">18px</strong>
    <strong onclick="font(20)">20px</strong>
    <strong onclick="font(22)">22px</strong>
    <strong onclick="font(24)">24px</strong>
</div>
<div id="color">
    <strong title="黑色" style="background: #000" onclick="showcolor('#000')"></strong>
    <strong title="褐色" style="background: #930" onclick="showcolor('#930')"></strong>
    <strong title="橄榄树" style="background: #330" onclick="showcolor('#330')"></strong>
    <strong title="深绿" style="background: #030" onclick="showcolor('#030')"></strong>
    <strong title="深青" style="background: #036" onclick="showcolor('#036')"></strong>
    <strong title="深蓝" style="background: #000080" onclick="showcolor('#000080')"></strong>
    <strong title="靓蓝" style="background: #339" onclick="showcolor('#339')"></strong>
    <strong title="灰色-80%" style="background: #333" onclick="showcolor('#333')"></strong>
    <strong title="深红" style="background: #800000" onclick="showcolor('#800000')"></strong>
    <strong title="橙红" style="background: #f60" onclick="showcolor('#f60')"></strong>
    <strong title="深黄" style="background: #808000" onclick="showcolor('#808000')"></strong>
    <strong title="深绿" style="background: #008000" onclick="showcolor('#008000')"></strong>
    <strong title="绿色" style="background: #008080" onclick="showcolor('#008080')"></strong>
    <strong title="蓝色" style="background: #00f" onclick="showcolor('#00f')"></strong>
    <strong title="蓝灰" style="background: #669" onclick="showcolor('#669')"></strong>
    <strong title="灰色-50%" style="background: #808080" onclick="showcolor('#808080')"></strong>
    <strong title="红色" style="background: #f00" onclick="showcolor('#f00')"></strong>
    <strong title="浅橙" style="background: #f90" onclick="showcolor('#f90')"></strong>
    <strong title="酸橙" style="background: #9c0" onclick="showcolor('#9c0')"></strong>
    <strong title="海绿" style="background: #396" onclick="showcolor('#396')"></strong>
    <strong title="水绿色" style="background: #3cc" onclick="showcolor('#3cc')"></strong>
    <strong title="浅蓝" style="background: #36f" onclick="showcolor('#36f')"></strong>
    <strong title="紫罗兰" style="background: #800080" onclick="showcolor('#800080')"></strong>
    <strong title="灰色-40%" style="background: #999" onclick="showcolor('#999')"></strong>
    <strong title="粉色" style="background: #f0f" onclick="showcolor('#f0f')"></strong>
    <strong title="金色" style="background: #fc0" onclick="showcolor('#fc0')"></strong>
    <strong title="黄色" style="background: #ff0" onclick="showcolor('#ff0')"></strong>
    <strong title="鲜绿" style="background: #0f0" onclick="showcolor('#0f0')"></strong>
    <strong title="青绿" style="background: #0ff" onclick="showcolor('#0ff')"></strong>
    <strong title="天蓝" style="background: #0cf" onclick="showcolor('#0cf')"></strong>
    <strong title="梅红" style="background: #936" onclick="showcolor('#936')"></strong>
    <strong title="灰色-20%" style="background: #dcdcdc" onclick="showcolor('#0c0c0c')"></strong>
    <strong title="玫瑰红" style="background: #f90" onclick="showcolor('#f90')"></strong>
    <strong title="茶色" style="background: #f9c" onclick="showcolor('#f9c')"></strong>
    <strong title="浅黄" style="background: #ff9" onclick="showcolor('#ff9')"></strong>
    <strong title="浅绿" style="background: #cfc" onclick="showcolor('#cfc')"></strong>
    <strong title="浅青绿" style="background: #cff" onclick="showcolor('#cff')"></strong>
    <strong title="浅蓝" style="background: #9cf" onclick="showcolor('#9cf')"></strong>
    <strong title="淡紫" style="background: #c9f" onclick="showcolor('#c9f')"></strong>
    <strong title="白色" style="background: #fff" onclick="showcolor('#fff')"></strong>
    <em><input type="text" name="t" placeholder="请输入颜色值(例:#ffffff)" /></em>
</div>