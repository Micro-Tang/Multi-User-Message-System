window.onload = function () {
    var up = document.getElementById('up');
    var fm = document.getElementsByTagName('form')[0];
    up.onclick = function () {
        centerWindow('upimg.php?dir='+this.title, '上传图片', 150, 400);
    };

    fm.onsubmit = function () {
        if (fm.name.value.length < 2 || fm.name.value.length > 20) {
            alert('图片名不得小于2位或者大于20位');
            return false;
        }

        if (fm.url.value.length == 0) {
            alert('图片地址不得为空');
            return false;
        }

        if (fm.content.value.length > 200) {
            alert('图片简介不得大于200位');
            return false;
        }
        return true;
    };
};
function centerWindow(url, name, height, width) {
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open(url, name, 'height='+height+',width='+width+',top='+top+',left='+left);
}